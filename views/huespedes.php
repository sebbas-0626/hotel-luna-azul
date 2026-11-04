<!-- Vista: Gestión de huéspedes -->
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Huéspedes - Hotel Luna Azul</title>
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
        <h2>Huéspedes</h2>
        <p style="color:var(--muted);margin:0;font-size:14px">Gestión de huéspedes registrados</p>
      </div>
      <button class="btn btn-primary" onclick="openForm()">+ Nuevo huésped</button>
    </div>
    <div class="search-box" style="margin-bottom:14px">
      <input id="search" placeholder="Buscar por nombre o documento">
    </div>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Nombre</th><th>Documento</th><th>Teléfono</th><th>Email</th><th>Acciones</th></tr></thead>
        <tbody id="tbody"></tbody>
      </table>
    </div>
    <div id="empty" class="empty-state" style="display:none">
      <div class="icon">👥</div>
      <p>No hay huéspedes registrados</p>
    </div>
  </div>
</div>

<div class="modal-backdrop" id="modal">
  <div class="modal">
    <h3 id="modalTitle">Huésped</h3>
    <form id="form">
      <input type="hidden" name="id">
      <div class="form-grid">
        <div><label>Nombre completo</label><input name="nombre_completo" required></div>
        <div><label>Tipo documento</label><select name="tipo_documento"><option>CC</option><option>CE</option><option>Pasaporte</option><option>TI</option></select></div>
        <div><label>Número documento</label><input name="numero_documento" required></div>
        <div><label>Dirección</label><input name="direccion"></div>
        <div><label>Teléfono</label><input name="telefono"></div>
        <div><label>Celular</label><input name="celular"></div>
        <div><label>Edad</label><input type="number" name="edad"></div>
        <div><label>Email</label><input type="email" name="email"></div>
        <div><label>Contacto emergencia</label><input name="contacto_emergencia"></div>
        <div><label>Parentesco</label><input name="parentesco_contacto"></div>
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
  const s = document.getElementById('search').value;
  const r = await API.get('/api/huespedes' + (s ? '?search=' + encodeURIComponent(s) : ''));
  const tb = document.getElementById('tbody');
  tb.innerHTML = '';
  const empty = document.getElementById('empty');
  if (!r.data.length) { empty.style.display = 'block'; return; }
  empty.style.display = 'none';
  r.data.forEach(h => {
    const tr = document.createElement('tr');
    tr.innerHTML = `<td><strong>${h.nombre_completo}</strong></td><td><span class="badge info">${h.tipo_documento}</span> ${h.numero_documento}</td><td>${h.telefono || h.celular || '-'}</td><td>${h.email || '-'}</td>
      <td class="actions">
        <button class="btn btn-secondary btn-sm" onclick="edit(${h.id})">Editar</button>
        <button class="btn btn-danger btn-sm" onclick="del(${h.id})">Eliminar</button>
      </td>`;
    tb.appendChild(tr);
  });
}
function openForm(data = null) {
  editingId = data ? data.id : null;
  document.getElementById('modalTitle').textContent = data ? 'Editar Huésped' : 'Nuevo Huésped';
  const form = document.getElementById('form');
  form.reset();
  if (data) {
    Object.keys(data).forEach(k => {
      const el = form.elements[k];
      if (el) el.value = data[k] ?? '';
    });
  }
  showModal('modal');
}
async function edit(id) {
  const r = await API.get('/api/huespedes/' + id);
  openForm(r.data);
}
async function del(id) {
  if (!confirm('¿Eliminar huésped?')) return;
  try { await API.del('/api/huespedes/' + id); toast('Eliminado'); load(); }
  catch (e) { toast(e.message, 'error'); }
}
document.getElementById('form').addEventListener('submit', async (e) => {
  e.preventDefault();
  const fd = new FormData(e.target);
  const body = Object.fromEntries(fd.entries());
  if (body.edad) body.edad = Number(body.edad);
  delete body.id;
  try {
    if (editingId) await API.put('/api/huespedes/' + editingId, body);
    else await API.post('/api/huespedes', body);
    editingId = null;
    hideModal('modal');
    toast('Guardado');
    await load();
  } catch (e) { toast(e.message, 'error'); }
});
document.getElementById('search').addEventListener('input', () => load());
requireAuth().then(u => { if (u) load(); });
document.getElementById('logout').addEventListener('click', async (e) => {
  e.preventDefault(); await API.post('/api/logout', {}); window.location.href = '/login';
});
</script>
</body>
</html>
