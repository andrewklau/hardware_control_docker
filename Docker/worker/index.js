var Docker = require('dockerode');
var fs = require('fs');
var stream = require('stream');
var request = require('request');

var socket = process.env.DOCKER_SOCKET || '/var/run/docker.sock';
var stats = fs.statSync(socket);

if (!stats.isSocket()) {
  throw new Error('Are you sure the docker is running?');
}

var docker = new Docker({
  socketPath: '/var/run/docker.sock'
});

/**
 * Get logs from running container
 */
function containerLogs(message, container, status, callback) {

  // create a single stream for stdin and stdout
  var logStream = new stream.PassThrough();
  logStream.on('data', function(chunk) {
    console.log(chunk);
  });

  container.logs({
    follow: true,
    stdout: true,
    stderr: true
  }, function(err, dockerStream) {
    if (err) throw err;

    var chunk = '';
    dockerStream.on('data', function(data) {
      console.log(data.toString());
      //TODO broadcast these back to pusher
      chunk += data.toString();
    });

    dockerStream.on('end', (code) => {
      request.post('http://192.168.3.165:8081/api/devices?api_token=123123', {
        form: {
          id: message.id,
          status: status,
          result: chunk,
        }
      }, function(err, data, obj) {
        if (err) throw err;
        console.log(obj);

        // Clean up
        container.remove(function(err, data) {
          if (err) throw err;
        });

      });
    });
  })

  if (callback) callback();
}

/*
 *  Expected Response:
 *   - id
 *   - user_id
 *   - name
 *   - source
 *   - task
 *   - device
 *   - status
 *   - result
 *   - limits
 *   - created_at
 *   - updated_at
 */

request({
  url: 'http://192.168.3.165:8081/api/devices?api_token=123123',
  method: 'GET',
}, function(err, data, obj) {
  if (err) throw err;
  if (obj) {
    // got a job
    var message = JSON.parse(obj);

    /* claim the job */
    request.post('http://192.168.3.165:8081/api/devices?api_token=123123', {
      form: {
        id: message.id,
        status: 'running'
      }
    }, function(err, data, obj) {
      if (err) throw err;
      console.log(obj);

      // docker run --rm andrewklau/raspbian-gpio --cap-add SYS_RAWIO --device /dev/mem --privileged
      console.log("Starting (" + message.name + ") limits container: " + message.limits)
      docker.createContainer({
        Image: 'andrewklau/raspbian-gpio',
        HostConfig: {
          "Memory": 134217728, //128MB
          "CapAdd": ["SYS_RAWIO"],
          "Privileged": true,
          "Devices": [{
            "PathOnHost": "/dev/mem",
            "PathInContainer": "/dev/mem",
            "CgroupPermissions": "mrw"
          }],
        },
        Env: [
          'WORKER_LIMITS=' + message.limits,
        ]
      }, function(err, container) {
        if (err) throw err;
        container.start({}, function(err, data) {
          containerLogs(message, container, 'running', function() {
            docker.createContainer({
              Image: 'andrewklau/raspbian-python',
              HostConfig: {
                "Binds": ["/sys:/sys"],
                "Memory": 134217728, //128MB
              },
              Env: [
                'WORKER_SOURCE=' + message.source,
                'WORKER_TASK=' + message.task
              ]
            }, function(err, container) {
              if (err) throw err;
              console.log("Starting the user container: " + message.task + " on " + message.source)
              container.start({}, function(err, data) {
                containerLogs(message, container, 'completed');
              });
            });
          });
        });
      });
    })
  }
});
