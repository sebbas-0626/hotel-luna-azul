// Cliente HTTP para consumir la API REST
const API = {
  async request(path, opts = {}) {
    const res = await fetch(path, {
      credentials: 'include',
      headers: { 'Content-Type': 'application/json', ...(opts.headers || {}) },
      ...opts,
      body: opts.body ? JSON.stringify(opts.body) : undefined,
    });
    let data = null;
    try { data = await res.json(); } catch (e) {}
    if (!res.ok) {
      const msg = data?.error || (data?.errors ? Object.values(data.errors).flat().join(', ') : 'Error');
      throw new Error(msg);
    }
    return data;
  },
  get: (p) => API.request(p),
  post: (p, b) => API.request(p, { method: 'POST', body: b }),
  put: (p, b) => API.request(p, { method: 'PUT', body: b }),
  del: (p) => API.request(p, { method: 'DELETE' }),
};

function toast(msg, type = 'success') {
  const el = document.getElementById('toast');
  if (!el) return;
  el.textContent = msg;
  el.className = 'toast ' + type;
  el.style.display = 'block';
  setTimeout(() => el.style.display = 'none', 3000);
}

function showModal(id) { document.getElementById(id).style.display = 'flex'; }
function hideModal(id) { document.getElementById(id).style.display = 'none'; }

async function requireAuth() {
  const r = await API.get('/api/me');
  if (!r.user) { window.location.href = '/login'; return null; }
  return r.user;
}
