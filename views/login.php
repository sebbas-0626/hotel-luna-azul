<!-- Vista: Login -->
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login - Hotel Luna Azul</title>
<link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="login-wrap">
  <div class="login-card">
    <div style="text-align:center;font-size:42px;margin-bottom:6px">🌙</div>
    <h2>Hotel Luna Azul</h2>
    <p class="subtitle">Iniciar sesión en el panel de gestión</p>
    <form id="loginForm">
      <label>Email</label>
      <input type="email" name="email" placeholder="admin@hotel.com" required>
      <label>Contraseña</label>
      <input type="password" name="password" placeholder="••••••••" required>
      <button class="btn btn-primary" type="submit" style="width:100%;margin-top:6px">Entrar</button>
    </form>
    <p id="err" style="color:var(--danger);margin-top:10px;text-align:center"></p>
  </div>
</div>
<div id="toast" class="toast"></div>
<script src="/js/api.js"></script>
<script>
document.getElementById('loginForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const fd = new FormData(e.target);
  const body = Object.fromEntries(fd.entries());
  try {
    await API.post('/api/login', body);
    window.location.href = '/';
  } catch (err) {
    document.getElementById('err').textContent = err.message;
  }
});
</script>
</body>
</html>
