const request = require('superagent');

function handleWitResponse(res) {
  return res.entities;
}

module.exports = function witClient(token) {
  const ask = function ask(message, cb) {
    request.get('https://api.wit.ai/message')
      .set('Authorization', `Bearer ${token}`)
      .query({
        v: '20170709&q',
      })
      .query({
        q: message,
      })
      .end((err, res) => {
        if (err) {
          return cb(err);
        }
        if (res.statusCode !== 200) {
          return cb(`Expected 200 response but received ${res.statusCode}`);
        }

        const witResponse = handleWitResponse(res.body);
        return cb(null, witResponse);
      });
  };

  return { ask };
};
