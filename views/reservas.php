<!-- Vista: Gestión de reservas -->
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Reservas - Hotel Luna Azul</title>
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
        <h2>Reservas</h2>
        <p style="color:var(--muted);margin:0;font-size:14px">Control de reservas del hotel</p>
      </div>
      <button class="btn btn-primary" onclick="openForm()">+ Nueva reserva</button>
    </div>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Huésped</th><th>Habitación</th><th>Tipo</th><th>Ingreso</th><th>Salida</th><th>Huéspedes</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody id="tbody"></tbody>
      </table>
    </div>
    <div id="empty" class="empty-state" style="display:none">
      <div class="icon">📅</div>
      <p>No hay reservas registradas</p>
    </div>
  </div>
</div>

<div class="modal-backdrop" id="modal">
  <div class="modal">
    <h3 id="modalTitle">Reserva</h3>
    <form id="form">
      <input type="hidden" name="id">
      <div class="form-grid">
        <div><label>Huésped</label><select name="huesped_id" id="huespedSelect" required></select></div>
        <div><label>Tipo habitación</label><select name="tipo_habitacion" id="tipoSelect"><option value="sencilla">Sencilla</option><option value="doble">Doble</option><option value="suite">Suite</option></select></div>
        <div><label>Fecha ingreso</label><input type="date" name="fecha_ingreso" required></div>
        <div><label>Fecha salida</label><input type="date" name="fecha_salida" required></div>
        <div><label>Nº huéspedes</label><input type="number" name="num_huespedes" value="1" min="1"></div>
        <div><label>Estado</label><select name="estado"><option value="confirmada">Confirmada</option><option value="cancelada">Cancelada</option></select></div>
      </div>
      <div style="margin:8px 0">
        <button type="button" class="btn btn-secondary" onclick="checkDisp()">Ver disponibilidad</button>
        <span id="dispInfo" style="margin-left:8px;font-size:13px;color:var(--muted)"></span>
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
  const r = await API.get('/api/reservas');
  const tb = document.getElementById('tbody');
  tb.innerHTML = '';
  const empty = document.getElementById('empty');
  if (!r.data.length) { empty.style.display = 'block'; return; }
  empty.style.display = 'none';
  r.data.forEach(x => {
    const estadoBadge = x.estado === 'confirmada' ? '<span class="badge success">Confirmada</span>' : '<span class="badge danger">Cancelada</span>';
    const tr = document.createElement('tr');
    tr.innerHTML = `<td><strong>${x.huesped?.nombre_completo || '-'}</strong></td><td>${x.habitacion?.numero || '-'}</td><td><span class="badge info">${x.tipo_habitacion || ''}</span></td><td>${String(x.fecha_ingreso).slice(0,10)}</td><td>${String(x.fecha_salida).slice(0,10)}</td><td>${x.num_huespedes}</td><td>${estadoBadge}</td>
      <td class="actions">
        <button class="btn btn-secondary btn-sm" onclick='edit(${JSON.stringify(x)})'>Editar</button>
        <button class="btn btn-danger btn-sm" onclick="del(${x.id})">Eliminar</button>
      </td>`;
    tb.appendChild(tr);
  });
}
async function loadHuespedes() {
  const r = await API.get('/api/huespedes');
  const sel = document.getElementById('huespedSelect');
  sel.innerHTML = '';
  r.data.forEach(h => {
    const opt = document.createElement('option');
    opt.value = h.id; opt.textContent = h.nombre_completo;
    sel.appendChild(opt);
  });
}
function openForm(data = null) {
  editingId = data ? data.id : null;
  document.getElementById('modalTitle').textContent = data ? 'Editar Reserva' : 'Nueva Reserva';
  const form = document.getElementById('form');
  form.reset();
  if (data) {
    form.elements['id'].value = data.id;
    form.elements['huesped_id'].value = data.huesped_id;
    form.elements['tipo_habitacion'].value = data.tipo_habitacion;
    form.elements['fecha_ingreso'].value = String(data.fecha_ingreso).slice(0,10);
    form.elements['fecha_salida'].value = String(data.fecha_salida).slice(0,10);
    form.elements['num_huespedes'].value = data.num_huespedes;
    form.elements['estado'].value = data.estado;
  }
  loadHuespedes();
  document.getElementById('dispInfo').textContent = '';
  showModal('modal');
}
function edit(x) { openForm(x); }
async function del(id) {
  if (!confirm('¿Eliminar reserva?')) return;
  try { await API.del('/api/reservas/' + id); toast('Eliminado'); load(); }
  catch (e) { toast(e.message, 'error'); }
}
async function checkDisp() {
  const form = document.getElementById('form');
  const ingreso = form.elements['fecha_ingreso'].value;
  const salida = form.elements['fecha_salida'].value;
  const tipo = form.elements['tipo_habitacion'].value;
  if (!ingreso || !salida) { toast('Indique fechas', 'error'); return; }
  try {
    const r = await API.get(`/api/disponibilidad?fecha_ingreso=${ingreso}&fecha_salida=${salida}&tipo=${tipo}`);
    document.getElementById('dispInfo').textContent = r.data.length + ' habitación(es) disponible(s): ' + r.data.map(h=>h.numero).join(', ');
  } catch (e) { toast(e.message, 'error'); }
}
document.getElementById('form').addEventListener('submit', async (e) => {
  e.preventDefault();
  const fd = new FormData(e.target);
  const body = Object.fromEntries(fd.entries());
  body.huesped_id = Number(body.huesped_id);
  body.num_huespedes = Number(body.num_huespedes);
  delete body.id;
  try {
    if (editingId) await API.put('/api/reservas/' + editingId, body);
    else await API.post('/api/reservas', body);
    editingId = null;
    hideModal('modal'); toast('Guardado'); await load();
  } catch (e) { toast(e.message, 'error'); }
});
requireAuth().then(u => { if (u) { load(); loadHuespedes(); } });
document.getElementById('logout').addEventListener('click', async (e) => {
  e.preventDefault(); await API.post('/api/logout', {}); window.location.href = '/login';
});
</script>
</body>
</html>
