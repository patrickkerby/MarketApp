const { spawnSync } = require('child_process');
const path = require('path');

const major = parseInt(process.versions.node.split('.')[0], 10);
const script = process.argv[2];
const args = process.argv.slice(3);
const scriptPath = path.join(__dirname, '..', script);

const nodeArgs = major >= 17
    ? ['--openssl-legacy-provider', scriptPath, ...args]
    : [scriptPath, ...args];

const result = spawnSync(process.execPath, nodeArgs, { stdio: 'inherit' });

process.exit(result.status === null ? 1 : result.status);
