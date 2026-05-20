// Deploy-script til at uploade frontend og server-api via FTP.
// config læses fra .env.deploy
// kør med npm run deploy.

require("dotenv").config({ path: ".env.deploy" });

if (!process.env.FTP_REMOTE_ROOT) {
  console.error("Error: FTP_REMOTE_ROOT is not set in your .env file.");
  process.exit(1);
}

const FtpDeploy = require("ftp-deploy");

const remoteRoot = process.env.FTP_REMOTE_ROOT;

// credentials fra .env.deploy
const credentials = {
  user: process.env.FTP_USER,
  password: process.env.FTP_PASSWORD,
  host: process.env.FTP_HOST,
  port: process.env.FTP_PORT ? Number(process.env.FTP_PORT) : 21,
  forcePasv: true,
  sftp: false,
};

// Uploader én mappe til FTP med progress-output i terminalen.
// deleteRemote: true sikrer at gamle filer fjernes på serveren.
function deploy(localRoot, remoteRoot, label, exclude = []) {
  const ftpDeploy = new FtpDeploy();
  const config = {
    ...credentials,
    localRoot,
    remoteRoot,
    include: ["*", "**/*", ".*", "**/.*"],
    exclude,
    deleteRemote: true,
  };

  ftpDeploy.on("uploading", (data) => {
    const percent = Math.round((data.transferredFileCount / data.totalFilesCount) * 100);
    process.stdout.write(`\r[${label}] [${percent}%] Uploading: ${data.filename}`);
  });

  ftpDeploy.on("uploaded", () => {
    process.stdout.write("\n");
  });

  return ftpDeploy.deploy(config);
}

// Deploy frontend først, derefter server-api (.env kommer ikke med)
deploy(__dirname + "/.output/public", remoteRoot, "frontend")
  .then(() => {
    console.log("Frontend deployed!");
    return deploy(__dirname + "/server-api", remoteRoot + "/server-api", "server-api", [".env"]);
  })
  .then(() => console.log("Deploy complete!"))
  .catch((err) => {
    console.error("Deploy failed:", err);
    process.exit(1);
  });
