<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mabúz Fast Food - Menú Digital V2</title>
    <style>
        /* --- ARQUITECTURA DE DISEÑO (CSS VARIABLES) --- */
        :root {
            --bg-principal: #0a0a0a;
            --bg-tarjeta: #141414;
            --bg-input: #1f1f1f;
            --accent-oro: #f59e0b;
            --accent-rojo: #ef4444;
            --texto-claro: #f3f4f6;
            --texto-mutado: #9ca3af;
            --fuente-sans: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            --curva-tarjeta: 12px;
            --transicion-suave: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --sombra-elegante: 0 10px 15px -3px rgba(0, 0, 0, 0.5), 0 4px 6px -2px rgba(0, 0, 0, 0.5);
        }

        /* --- RESET & CONFIGURACIÓN BASE --- */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-principal);
            color: var(--texto-claro);
            font-family: var(--fuente-sans);
            line-height: 1.6;
            overflow-x: hidden;
            padding-bottom: 60px;
        }

        /* --- CONTENEDORES SEMÁNTICOS --- */
        header {
            text-align: center;
            padding: 40px 20px;
            background: linear-gradient(180deg, rgba(245, 158, 11, 0.1) 0%, rgba(10, 10, 10, 0) 100%);
        }

        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* Control profesional del contenedor del Logo */
        .logo-img {
            max-width: 250px;
            height: auto;
            object-fit: contain;
            transition: var(--transicion-suave);
        }

        /* Clase de accesibilidad (Oculta texto visualmente pero lo mantiene para SEO y lectores de pantalla) */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
        }

        /* --- FILTROS DE NAVEGACIÓN --- */
        nav {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            padding: 10px 20px 30px 20px;
            position: sticky;
            top: 0;
            background-color: rgba(10, 10, 10, 0.85);
            backdrop-filter: blur(10px);
            z-index: 100;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .btn-filtro {
            background-color: var(--bg-tarjeta);
            color: var(--texto-claro);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 10px 20px;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95rem;
            transition: var(--transicion-suave);
        }

        .btn-filtro:hover, .btn-filtro.activo {
            background-color: var(--accent-oro);
            color: var(--bg-principal);
            border-color: var(--accent-oro);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .menu-seccion {
            margin-bottom: 50px;
        }

        .seccion-titulo {
            font-size: 1.8rem;
            color: var(--texto-claro);
            margin-bottom: 25px;
            border-left: 5px solid var(--accent-rojo);
            padding-left: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* --- GRID DE PRODUCTOS --- */
        .grid-productos {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        /* --- TARJETA DE PRODUCTO --- */
        .tarjeta-producto {
            background-color: var(--bg-tarjeta);
            border-radius: var(--curva-tarjeta);
            overflow: hidden;
            box-shadow: var(--sombra-elegante);
            border: 1px solid rgba(255, 255, 255, 0.03);
            transition: var(--transicion-suave);
            opacity: 0;
            transform: translateY(30px);
        }

        .tarjeta-producto.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .tarjeta-producto:hover {
            transform: translateY(-8px);
            border-color: rgba(245, 158, 11, 0.3);
            box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.7);
        }

        /* --- CONTENEDOR DE IMAGEN PROFESIONAL (SKELETON LOADER) --- */
        .contenedor-imagen {
            position: relative;
            width: 100%;
            padding-top: 66.66%; /* Relación de aspecto 3:2 */
            background-color: #1a1a1a;
            overflow: hidden;
        }

        .contenedor-imagen::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #1a1a1a 25%, #262626 50%, #1a1a1a 75%);
            background-size: 200% 100%;
            animation: pulse-skeleton 1.5s infinite linear;
            z-index: 1;
            transition: opacity 0.5s ease;
        }

        .contenedor-imagen.cargada::before {
            opacity: 0;
            pointer-events: none;
        }

        .contenedor-imagen img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1), transform 0.5s ease;
            z-index: 2;
        }

        .contenedor-imagen.cargada img {
            opacity: 1;
        }

        .tarjeta-producto:hover .contenedor-imagen img {
            transform: scale(1.05);
        }

        .badge-categoria {
            position: absolute;
            top: 12px;
            right: 12px;
            background-color: rgba(10, 10, 10, 0.75);
            backdrop-filter: blur(4px);
            color: var(--accent-oro);
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            z-index: 3;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        /* --- DETALLES E INFO DE TARJETA --- */
        .info-producto {
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: calc(100% - 66.66vw * 0.28);
            min-height: 160px;
        }

        .meta-titulo {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 8px;
        }

        .meta-titulo h3 {
            font-size: 1.15rem;
            color: var(--texto-claro);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .precio-unico {
            color: var(--accent-oro);
            font-weight: 700;
            font-size: 1.2rem;
            white-space: nowrap;
        }

        .descripcion-producto {
            font-size: 0.88rem;
            color: var(--texto-mutado);
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-grow: 1;
        }

        .contenedor-precios-variados {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 6px;
            padding: 8px 12px;
            margin-top: auto;
            border: 1px solid rgba(255, 255, 255, 0.02);
        }

        .fila-precio {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            margin-bottom: 4px;
        }

        .fila-precio:last-child {
            margin-bottom: 0;
        }

        .label-variante {
            color: var(--texto-mutado);
            text-transform: uppercase;
            font-weight: 500;
        }

        .valor-variante {
            color: var(--accent-oro);
            font-weight: 700;
        }

        /* --- GRID ADICIONALES --- */
        .grid-adicionales {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .tarjeta-adicional {
            background-color: var(--bg-tarjeta);
            border-radius: 8px;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(255, 255, 255, 0.03);
            box-shadow: var(--sombra-elegante);
            opacity: 0;
            transform: translateY(20px);
            transition: var(--transicion-suave);
        }

        .tarjeta-adicional.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .info-adicional h4 {
            font-size: 0.95rem;
            text-transform: uppercase;
            color: var(--texto-claro);
        }

        .info-adicional p {
            font-size: 0.75rem;
            color: var(--texto-mutado);
        }

        .precio-adicional {
            color: var(--accent-oro);
            font-weight: 700;
            font-size: 1rem;
        }

        @keyframes pulse-skeleton {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        @media (max-width: 600px) {
            .logo-img { max-width: 180px; }
            nav { padding-bottom: 20px; }
            .btn-filtro { padding: 8px 14px; font-size: 0.85rem; }
            .grid-productos { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <header>
        <div class="logo-container">
            <!-- 
                [MODIFICACIÓN ARQUITECTÓNICA]: 
                Reemplaza "ruta/de/tu/logo-mabuz.png" por la ruta local o URL real de tu logotipo.
                Mantenemos las clases estructurales ocultas (.sr-only) para que los motores de búsqueda (SEO) 
                y lectores de pantalla sigan indexando el nombre del negocio aunque sea una imagen.
            -->
            <img src="logo.png" alt="Mabúz Fast Food" class="logo-img">
            <h1 class="sr-only">Mabúz</h1>
            <p class="sr-only">Fast Food</p>
        </div>
    </header>

    <nav id="menu-filtros">
        <button class="btn-filtro activo" data-categoria="todos">Todos</button>
        <button class="btn-filtro" data-categoria="pizzas">Pizzas</button>
        <button class="btn-filtro" data-categoria="hamburguesas">Hamburguesas</button>
        <button class="btn-filtro" data-categoria="piezas">Piezas</button>
        <button class="btn-filtro" data-categoria="bebidas">Bebidas</button>
        <button class="btn-filtro" data-categoria="adicionales">Toppings</button>
    </nav>

    <main id="menu-render-target">
        <!-- Inyección reactiva -->
    </main>

    <script>
        /* --- ARQUITECTURA DE DATOS (DATA LAYER V2) ---
           He añadido la propiedad "imagen" a cada objeto. No cometas el error de novato de quemar paths en el HTML.
           Simplemente sustituye el valor por defecto ("ruta/de/tu/imagen...") por el archivo real (ej: "images/margarita.jpg").
           Si dejas el string vacío o no encuentra la imagen, el sistema usará un placeholder automático para evitar romper la UI.
        */
        const MABUZ_DATA = {
            pizzas: [
                { id: "p1", nombre: "Margarita", ing: "Jamón, Mozzarella, Salsa Napolitana, Masa de la Casa", precios: { mediana: "25.000", familiar: "40.000" }, imagen: "Margarita.jpg" },
                { id: "p2", nombre: "Pepperoni", ing: "Pepperoni, Mozarella, Masa de la Casa", precios: { mediana: "30.000", familiar: "45.000" }, imagen: "Pepperoni.jpg" },
                { id: "p3", nombre: "Hawaiana", ing: "Piña, Mozzarella, Jamón, Masa de la Casa", precios: { mediana: "30.000", familiar: "50.000" }, imagen: "Hawaiana.jpg" },
                { id: "p4", nombre: "Cheddar", ing: "Mozzarella, Jamón de Pierna, Queso Cheddar, Tocineta Crujiente, Masa de la Casa", precios: { mediana: "30.000", familiar: "50.000" }, imagen: "Cheddar.jpg" },
                { id: "p5", nombre: "4 Estaciones", ing: "Tocineta, Pepperoni, Jamón Ahumado, Maíz, Masa de la Casa", precios: { mediana: "35.000", familiar: "55.000" }, imagen: "4estaciones.jpg" },
                { id: "p6", nombre: "Bacon", ing: "Jamón Ahumado, Maíz, Tocineta, Salsa Bacon, Mozzarella, Masa de la Casa", precios: { mediana: "35.000", familiar: "55.000" }, imagen: "pizzabacon.jpg" },
                { id: "p7", nombre: "Red", ing: "Pepperoni, Salami, Carne de Pizza, Jamón Ahumado, Tocineta, Masa de la Casa", precios: { mediana: "40.000", familiar: "65.000" }, imagen: "Cheddar.jpg" }
            ],
            hamburguesas: [
                { id: "h1", nombre: "Bacon", ing: "Pan de papa, Carne 150g, Doble Cheddar, Mermelada de Tocineta 100g, Lechuga, Tomate, Big Mac, 100g de Papas Fritas", precio: "35.000" , imagen: "hambacon.jpg" },
                { id: "h2", nombre: "Americana", ing: "Pan Brioche, Carne 150g, Cheddar, Cebolla Caramelizada, Tocineta, Lechuga, Tomate, Big Mac, 100g de Papas Fritas", precio: "25.000" , imagen: "Americana.jpg" }
            ],
            piezas: [
                { id: "pz1", nombre: "Pastel de Carne", ing: "Pieza individual crujiente rellena de carne sazonada", precio: "2.500", imagen: "Tequeños_y_pasteles.jpg" },
                { id: "pz2", nombre: "Pastel de Papa", ing: "Pieza crujiente con suave puré de papa y condimentos", precio: "2.500", imagen: "Tequeños_y_pasteles.jpg" },
                { id: "pz3", nombre: "Moñongos", ing: "Especialidad horneada o frita de la casa", precio: "8.000", imagen: "Moñongo.jpg" },
                { id: "pz4", nombre: "Tequeños", ing: "Dedos de masa tradicional rellenos de queso artesanal (unidad)", precio: "2.500", imagen: "Tequeños_y_pasteles.jpg" },
                { id: "pz5", nombre: "Tequeños de Mozzarella", ing: "Deditos gourmet rellenos con queso mozzarella fundente", precio: "4.000", imagen: "Tequeños_de_mozzarella .jpg" },
                { id: "pz6", nombre: "Pasteles de Pizza", ing: "Pastelitos crujientes rellenos con sabor e ingredientes de pizza", precio: "3.000", imagen: "Tequeños_y_pasteles.jpg" }
            ],
            bebidas: [
                { id: "b1", nombre: "Refrescos de Botella", ing: "Variedad de sabores en presentación individual", precio: "4.000", imagen: "ruta/de/tu/refresco-botella.jpg" },
                { id: "b2", nombre: "Refrescos 1L", ing: "Formato familiar de un litro para compartir", precio: "5.000", imagen: "ruta/de/tu/refresco-1l.jpg" },
                { id: "b3", nombre: "Refrescos 2L", ing: "Formato grande ideal para el grupo", precio: "8.000", imagen: "ruta/de/tu/refresco-2l.jpg" },
                { id: "b4", nombre: "Malta de Botella", ing: "Bebida de malta fría presentación individual", precio: "3.000", imagen: "ruta/de/tu/malta-botella.jpg" },
                { id: "b5", nombre: "Malta 1.5L", ing: "Presentación grande para los fanáticos de la malta", precio: "10.000", imagen: "ruta/de/tu/malta-15l.jpg" },
                { id: "b6", nombre: "Jugo El Valle", ing: "Refrescante jugo de frutas seleccionadas", precio: "8.000", imagen: "ruta/de/tu/jugo-elvalle.jpg" },
                { id: "b7", nombre: "Cervezas", ing: "Cerveza fría para acompañar tus platos", precio: "3.000", imagen: "ruta/de/tu/cerveza.jpg" }
            ],
            adicionales: [
                { id: "a1", nombre: "Vegetales", sub: "Maíz / Pimentón / Cebolla", precio: "3.000" },
                { id: "a2", nombre: "Pepperoni", sub: "Porción extra premium", precio: "8.000" },
                { id: "a3", nombre: "Aceitunas Negras", sub: "Tajadas finas locales", precio: "4.000" },
                { id: "a4", nombre: "Salami", sub: "Embutido madurado de alta calidad", precio: "8.000" },
                { id: "a5", nombre: "Champiñones", sub: "Hongos fileteados frescos", precio: "6.000" },
                { id: "a6", nombre: "Tocineta", sub: "Tiras crujientes adicionales", precio: "5.000" }
            ]
        };

        const target = document.getElementById("menu-render-target");
        const filtros = document.querySelectorAll(".btn-filtro");

        const scrollObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("visible");
                    scrollObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.05, rootMargin: "0px 0px -30px 0px" });

        function verificarYAsignarImagenes() {
            const contenedores = document.querySelectorAll(".contenedor-imagen");
            contenedores.forEach(contenedor => {
                const img = contenedor.querySelector("img");
                
                // Si hay un error al cargar la imagen real (no existe la ruta), disparamos un fallback elegante en SVG
                img.addEventListener("error", () => {
                    img.src = `data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="300" height="200" viewBox="0 0 300 200"><rect width="300" height="200" fill="%231a1a1a"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="%23e5e7eb" font-family="sans-serif" font-weight="bold" font-size="14">MABÚZ FAST FOOD</text></svg>`;
                });

                if (img.complete) {
                    contenedor.classList.add("cargada");
                } else {
                    img.addEventListener("load", () => {
                        contenedor.classList.add("cargada");
                    });
                }
            });
        }

        function renderMenu(categoriaFiltro = "todos") {
            target.innerHTML = "";

            Object.keys(MABUZ_DATA).forEach(catKey => {
                if (categoriaFiltro !== "todos" && categoriaFiltro !== catKey) return;

                const seccion = document.createElement("section");
                seccion.className = "menu-seccion";
                
                const titulo = document.createElement("h2");
                titulo.className = "seccion-titulo";
                titulo.textContent = catKey === "adicionales" ? "Adicionales / Toppings" : catKey;
                seccion.appendChild(titulo);

                if (catKey === "adicionales") {
                    const grid = document.createElement("div");
                    grid.className = "grid-adicionales";
                    
                    MABUZ_DATA[catKey].forEach(item => {
                        const card = document.createElement("div");
                        card.className = "tarjeta-adicional";
                        card.innerHTML = `
                            <div class="info-adicional">
                                <h4>${item.nombre}</h4>
                                <p>${item.sub}</p>
                            </div>
                            <div class="precio-adicional">$${item.precio}</div>
                        `;
                        grid.appendChild(card);
                        scrollObserver.observe(card);
                    });
                    seccion.appendChild(grid);
                } else {
                    const grid = document.createElement("div");
                    grid.className = "grid-productos";

                    MABUZ_DATA[catKey].forEach(item => {
                        const article = document.createElement("article");
                        article.className = "tarjeta-producto";
                        
                        let selectorPrecio = `<div class="precio-unicom font-bold">$${item.precio}</div>`;
                        if (item.precios) {
                            selectorPrecio = `
                                <div class="contenedor-precios-variados">
                                    <div class="fila-precio">
                                        <span class="label-variante">Mediana</span>
                                        <span class="valor-variante">$${item.precios.mediana}</span>
                                    </div>
                                    <div class="fila-precio">
                                        <span class="label-variante">Familiar</span>
                                        <span class="valor-variante">$${item.precios.familiar}</span>
                                    </div>
                                </div>
                            `;
                        }

                        // Mapeo directo de la propiedad "imagen" del Data Layer
                        article.innerHTML = `
                            <div class="contenedor-imagen">
                                <span class="badge-categoria">${catKey}</span>
                                <img src="${item.imagen}" alt="${item.nombre}" loading="lazy">
                            </div>
                            <div class="info-producto">
                                <div class="meta-titulo">
                                    <h3>${item.nombre}</h3>
                                    ${!item.precios ? `<span class="precio-unico">$${item.precio}</span>` : ''}
                                </div>
                                <p class="descripcion-producto">${item.ing}</p>
                                ${item.precios ? selectorPrecio : ''}
                            </div>
                        `;
                        grid.appendChild(article);
                        scrollObserver.observe(article);
                    });
                    seccion.appendChild(grid);
                }

                target.appendChild(seccion);
            });

            verificarYAsignarImagenes();
        }

        filtros.forEach(btn => {
            btn.addEventListener("click", (e) => {
                filtros.forEach(b => b.classList.remove("activo"));
                e.target.classList.add("activo");
                const cat = e.target.getAttribute("data-categoria");
                renderMenu(cat);
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            renderMenu();
        });
    </script>
</body>
</html>
