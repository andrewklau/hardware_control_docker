var Docker = require('dockerode');
var fs = require('fs');
var stream = require('stream');
var request = require('request');
var sleep = require('sleep');

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
      request.post(process.env.SCHEDULER_URL + '/api/devices?api_token=' + process.env.SCHEDULER_TOKEN, {
        form: {
          id: message.id,
          status: status,
          result: chunk,
        }
      }, function(err, data, obj) {
        if (err && data.statusCode != 200) {
          throw err;
        } else {
          // Clean up
          container.remove(function(err, data) {
            if (err) throw err;

            if (status == 'completed') {
              console.log("Finished running job (" + message.name + ")")

              // back to job loop
              sleep.sleep(10);
              startJob();
            }
          });
        }
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

function startJob() {
  request({
    url: process.env.SCHEDULER_URL + '/api/devices?api_token=' + process.env.SCHEDULER_TOKEN,
    method: 'GET',
  }, function(err, data, obj) {
    if (err && data.statusCode != 200) {
      throw err;
    } else if (obj) {
      // got a job
      var message = JSON.parse(obj);

      /* claim the job */
      request.post(process.env.SCHEDULER_URL + '/api/devices?api_token=' + process.env.SCHEDULER_TOKEN, {
        form: {
          id: message.id,
          status: 'running'
        }
      }, function(err, data, obj) {
        if (err && data.statusCode != 200) {
          throw err;
        } else {
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
        }
      });
    } else {
      // No jobs (come back in 1 minute)
      console.log("No jobs.. sleeping for 60 seconds...");
      sleep.sleep(60);

      // back to job loop
      startJob();
    }
  });
}

/* The infinite loop */
const cluster = require('cluster');
if (cluster.isMaster) {
  cluster.fork();

  cluster.on('exit', function(worker, code, signal) {
    cluster.fork();
  });
}
if (cluster.isWorker) {
  // Start the job loop
  console.log("Starting worker...");
  startJob();
}
