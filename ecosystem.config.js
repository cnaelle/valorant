module.exports = {
  apps : [
    {
      name : `${__dirname}`,
      port: 3000,
      exec_mode: 'cluster',
      instances: 'max',
      script : `${__dirname}/front/.output/server/index.mjs`,
      watch : false,
      error_file : "/var/log/pm2/err.log",
      out_file : "/var/log/pm2/out.log"
    }
  ]
}
