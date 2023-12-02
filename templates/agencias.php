<?php
/*
* Template Name: Agencias
*/

get_header(); ?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<div class="jumbotron jumbotron-fluid">
	<div class="container d-flex align-items-center">
		<h1><?php echo the_title() ?></h1>
	</div>
</div>


<style>
	.container {
		display:flex;
		flex-wrap: wrap;
		justify-content: space-around;
	}
	.card {
		width:100%;
		max-width:500px;
		margin: 10px;
		background-color: #fff;
		border-radius: 8px;
		overflow: hidden;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		box-sizing: border-box; /* Certifica-se de que a largura inclui a margem */
	}
        .card a {
            text-decoration: none;
            color: inherit;
        }

        .cover {
            position: relative;
            height: 200px; 
            overflow: hidden;
        }

        .cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .infos {
            position: relative;
            padding: 16px;
            text-align: center; 
        }
		.overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }
		.logo {
			position: absolute !important;
			top: 50% !important;
			left: 50% !important;
			transform: translate(-50%, -50%) !important;
			max-width: 150px; 
			max-height: 150px; 
			height: auto !important;
			z-index: 3;
		}



        h5 {
            margin-bottom: 10px;
            font-size: 1.2em;
        }

        p {
            margin: 8px 0;
            font-size: 0.9em;
            color: #666;
        }

        small {
            display: block;
            margin-top: 10px;
            font-size: 0.8em;
            color: #888;
        }
    </style>
</head>
<body>

<section>
        <div class="container" id="agencyContainer">
	
		</div>
    </section>

    <script>
         $(document).ready(function () {
            // Faz uma requisição AJAX para a URL do JSON
            $.getJSON("https://dev.astronomiabrasil.com/api/agencias.json", function (data) {
                // Filtra as agências com valores atribuídos a image_url e logo_url
                var agenciasFiltradas = data.filter(function (agencia) {
                    return agencia.image_url && agencia.logo_url;
                });

                // Adiciona as agências ao container
                agenciasFiltradas.forEach(function (agencia) {
                    // Verifica se há valor para lançadores
                    var lancadoresText = agencia.launchers ? agencia.launchers : "Sem lançadores";

                    // Verifica se há valor para espaçonaves
                    var espacoNavesText = agencia.spacecraft ? agencia.spacecraft : "Sem espaçonaves";

                    var cardHtml = `
                        <div class="card list gallery">
                            <a href="#" title="${agencia.name}">
                                <div class="cover">
                                    <img src="${agencia.image_url}" alt="Cover">
                                    <div class="overlay"></div>
                                    <img src="${agencia.logo_url}" alt="Logo da Agência" class="logo">
                                </div>
                                <div class="infos">
                                    <h5>${agencia.name}</h5>
                                    <p>Instituto: ${agencia.type}</p>
                                    <p>Região: ${agencia.country_code}</p>
                                    <p>Administrator: ${agencia.administrator}</p>
                                    <p>Lançadores: ${lancadoresText}</p>
                                    <p>Espaçonaves: ${espacoNavesText}</p>
                                    <small>${agencia.conteudo_traduzido}</small>
                                </div>
                            </a>
                        </div>
                    `;

                    $("#agencyContainer").append(cardHtml);
                });
            });
        });
    </script>

<?php get_footer(); ?>
