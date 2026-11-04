<!-- Vista: Dashboard -->
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard - Hotel Luna Azul</title>
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
    <h1>Panel de Control</h1>
    <p style="color:var(--muted)">Bienvenido al sistema de gestión del Hotel Luna Azul.</p>
    <div class="stat-grid">
      <div class="stat-card">
        <div class="stat-icon blue">👥</div>
        <div><div class="stat-value" id="cHuespedes">-</div><div class="stat-label">Huéspedes registrados</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon amber">📅</div>
        <div><div class="stat-value" id="cReservas">-</div><div class="stat-label">Reservas activas</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon green">🛏️</div>
        <div><div class="stat-value" id="cHab">-</div><div class="stat-label">Habitaciones</div></div>
      </div>
    </div>
    <div style="margin-top:22px;display:flex;gap:10px;flex-wrap:wrap">
      <a class="btn btn-primary" href="/huespedes">Gestionar huéspedes</a>
      <a class="btn btn-accent" href="/reservas">Gestionar reservas</a>
      <a class="btn btn-secondary" href="/habitaciones">Gestionar habitaciones</a>
    </div>
  </div>
</div>
<div id="toast" class="toast"></div>
<script src="/js/api.js"></script>
<script>
requireAuth().then(u => {
  if (!u) return;
  API.get('/api/huespedes').then(r => document.getElementById('cHuespedes').textContent = r.data.length);
  API.get('/api/reservas').then(r => document.getElementById('cReservas').textContent = r.data.length);
  API.get('/api/habitaciones').then(r => document.getElementById('cHab').textContent = r.data.length);
});
document.getElementById('logout').addEventListener('click', async (e) => {
  e.preventDefault();
  await API.post('/api/logout', {});
  window.location.href = '/login';
});
</script>
</body>
</html>
