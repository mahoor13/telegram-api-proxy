const whitelist = ["/bot123456789:"];
const tg_host = "api.telegram.org";

function validate(path) {
    for (var i = 0; i < whitelist.length; i++) {
        if (path.startsWith(whitelist[i]) || path.startsWith("/file" + whitelist[i]))
            return true; 
    }
    return false;
}

export default {
  async fetch(request, env, ctx) {
    var u = new URL(request.url);
    u.host = tg_host;
    if (!validate(u.pathname))
        return new Response('Unauthorized', {
            status: 403
        });
    var req = new Request(u, {
        method: request.method,
        headers: request.headers,
        body: request.body
    });
    const result = await fetch(req);
    return result;
  },
};