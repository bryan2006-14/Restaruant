<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restaurante | 🅱</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: #f4f4f8;
      color: #333;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    header {
      background: #1e1e2f;
      color: white;
      padding: 2rem 1rem;
      text-align: center;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    header h1 {
      margin: 0;
      font-size: 2.2rem;
    }

    nav {
      display: flex;
      justify-content: center;
      gap: 2rem;
      margin-top: 1.5rem;
    }

    nav a {
      text-decoration: none;
      color: white;
      background: #ff4d4d;
      padding: 0.7rem 1.5rem;
      border-radius: 8px;
      transition: background 0.3s ease;
      font-weight: 600;
    }

    nav a:hover {
      background: #e63946;
    }

    main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    footer {
      text-align: center;
      padding: 1rem;
      font-size: 0.9rem;
      background: #e9ecef;
      color: #666;
    }

  main {
    background-image: url('./image/food_rest.webp');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    padding: 2rem;
    color: white;
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
    min-height: 300px; /* Ajusta según lo que necesites */
  }
  main p {
	  font-size: 1.2rem;
	  margin: 0;
	  text-align: center;
	}

	@media (max-width: 600px) {
	  header h1 {
		font-size: 1.8rem;
	  }

	  nav a {
		padding: 0.5rem 1rem;
		font-size: 0.9rem;
	  }

	  main p {
		font-size: 1rem;
	  }
	}

  </style>
</head>
<body>

  <header>
    <h1>Restaurante | 🅱</h1>
    <nav>
      <a href="staff">Empleado</a>
      <a href="admin">Administrador</a>
    </nav>
  </header>

  <main >
	
    <p>Bienvenido al sistema del restaurante. Selecciona una opción para continuar.</p>
  </main>

  <footer>
    &copy; 2025 Restaurante 🅱. Todos los derechos reservados.
  </footer>

  <!-- Scripts opcionales -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
</body>
</html>
