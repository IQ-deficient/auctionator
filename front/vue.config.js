let ip = 'localhost';
let port = '8000';

module.exports = {
  devServer: {
    proxy: {
      '^/api': {
        target: 'http://' + ip + ':' + port,
      },
    },
    disableHostCheck: true
  }
};