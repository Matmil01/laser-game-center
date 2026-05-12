require("dotenv").config({ path: ".env.deploy" });

if (!process.env.FTP_REMOTE_ROOT) {
  console.error("Error: FTP_REMOTE_ROOT is not set in your .env file.");
  process.exit(1);
}

const FtpDeploy = require("ftp-deploy");

const remoteRoot = process.env.FTP_REMOTE_ROOT;
const credentials = {
  user: process.env.FTP_USER,
  password: process.env.FTP_PASSWORD,
  host: process.env.FTP_HOST,
  port: process.env.FTP_PORT ? Number(process.env.FTP_PORT) : 21,
  forcePasv: true,
  sftp: false,
};

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
