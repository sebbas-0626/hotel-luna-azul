<!-- Vista: Gestión de habitaciones -->
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Habitaciones - Hotel Luna Azul</title>
<link rel="stylesheet" href="/css/style.css">
</head>
<body>
<nav class="navbar">
  <div class="brand">🌙 Hotel Luna Azul</div>
  <div>
    <a href="/">Inicio</a>
    <a href="/huespedes">Huéspedes</a>
    <a href="/habitaciones">Habitaciones</a>
    <a href="/reservas">Reservas</a>
    <a href="#" id="logout">Salir</a>
  </div>
</nav>
<div class="container">
  <div class="card">
    <div class="page-header">
      <div>
        <h2>Habitaciones</h2>
        <p style="color:var(--muted);margin:0;font-size:14px">Inventario y estado de habitaciones</p>
      </div>
      <button class="btn btn-primary" onclick="openForm()">+ Nueva habitación</button>
    </div>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Número</th><th>Tipo</th><th>Capacidad</th><th>Precio</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody id="tbody"></tbody>
      </table>
    </div>
    <div id="empty" class="empty-state" style="display:none">
      <div class="icon">🛏️</div>
      <p>No hay habitaciones registradas</p>
    </div>
  </div>
</div>
<div class="modal-backdrop" id="modal">
  <div class="modal">
    <h3>Habitación</h3>
    <form id="form">
      <input type="hidden" name="id">
      <div class="form-grid">
        <div><label>Número</label><input name="numero" required></div>
        <div><label>Tipo</label><select name="tipo"><option value="sencilla">Sencilla</option><option value="doble">Doble</option><option value="suite">Suite</option></select></div>
        <div><label>Capacidad</label><input type="number" name="capacidad" value="1"></div>
        <div><label>Precio</label><input type="number" name="precio" value="0"></div>
        <div><label>Estado</label><select name="estado"><option value="disponible">Disponible</option><option value="mantenimiento">Mantenimiento</option></select></div>
      </div>
      <div style="text-align:right">
        <button type="button" class="btn btn-secondary" onclick="hideModal('modal')">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>
<div id="toast" class="toast"></div>
<script src="/js/api.js"></script>
<script>
let editingId = null;
async function load() {
  const r = await API.get('/api/habitaciones');
  const tb = document.getElementById('tbody');
  tb.innerHTML = '';
  const empty = document.getElementById('empty');
  if (!r.data.length) { empty.style.display = 'block'; return; }
  empty.style.display = 'none';
  r.data.forEach(h => {
    const estadoBadge = h.estado === 'disponible' ? '<span class="badge success">Disponible</span>' : '<span class="badge muted">Mantenimiento</span>';
    const tr = document.createElement('tr');
    tr.innerHTML = `<td><strong>${h.numero}</strong></td><td><span class="badge info">${h.tipo}</span></td><td>${h.capacidad}</td><td>$${Number(h.precio).toLocaleString()}</td><td>${estadoBadge}</td>
      <td class="actions">
        <button class="btn btn-secondary btn-sm" onclick='edit(${JSON.stringify(h)})'>Editar</button>
        <button class="btn btn-danger btn-sm" onclick="del(${h.id})">Eliminar</button>
      </td>`;
    tb.appendChild(tr);
  });
}
function openForm(data = null) {
  editingId = data ? data.id : null;
  const form = document.getElementById('form');
  form.reset();
  if (data) Object.keys(data).forEach(k => { const el = form.elements[k]; if (el) el.value = data[k] ?? ''; });
  showModal('modal');
}

function edit(h) { openForm(h); }
async function del(id) {
  if (!confirm('¿Eliminar habitación?')) return;
  try { await API.del('/api/habitaciones/' + id); toast('Eliminado'); load(); }
  catch (e) { toast(e.message, 'error'); }
}
document.getElementById('form').addEventListener('submit', async (e) => {
  e.preventDefault();
  const fd = new FormData(e.target);
  const body = Object.fromEntries(fd.entries());
  if (body.capacidad) body.capacidad = Number(body.capacidad);
  if (body.precio) body.precio = Number(body.precio);
  delete body.id;
  try {
    if (editingId) await API.put('/api/habitaciones/' + editingId, body);
    else await API.post('/api/habitaciones', body);
    editingId = null;
    hideModal('modal'); toast('Guardado'); await load();
  } catch (e) { toast(e.message, 'error'); }
});
requireAuth().then(u => { if (u) load(); });
document.getElementById('logout').addEventListener('click', async (e) => {
  e.preventDefault(); await API.post('/api/logout', {}); window.location.href = '/login';
});
</script>
</body>
</html>
