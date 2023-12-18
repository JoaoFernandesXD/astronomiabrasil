// By Joao Fernandes
$(document).ready(function() {
    // Selecione todos os elementos com a classe "countdown-card"
    var countdownElements = document.querySelectorAll('.countdown-card');

    countdownElements.forEach(function(element) {
    // Obtenha a data e hora do evento a partir do atributo "data-event-date"
    var eventDate = new Date(element.getAttribute('data-event-date')).getTime();

    // Atualize o contador a cada segundo
    var countdown = setInterval(function() {
        // Obtenha a data e hora atual
        var now = new Date().getTime();

        // Calcule a diferença entre a data do evento e a data atual
        var distance = eventDate - now;

        // Se a contagem regressiva terminar, exiba uma mensagem ou execute ação desejada
        if (distance <= 0) {
        clearInterval(countdown);
        element.innerHTML = 'O lançamento começou!';
        } else {
        // Calcule os dias, horas, minutos e segundos restantes
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Construa a string da contagem regressiva exibindo apenas as partes relevantes
        var countdownText = '';
        if (days > 0) {
            countdownText += days + 'D ';
        }
        if (hours > 0) {
            countdownText += hours + 'hrs ';
        }
        if (minutes > 0) {
            countdownText += minutes + 'min ';
        }
        countdownText += seconds + 's ';

        // Exiba a contagem regressiva no elemento HTML
        element.innerHTML = 'Restam ' + countdownText;
        }
    }, 1000); // Atualize a cada segundo (1000 milissegundos)
    });



	var ajaxurl = '/wp-admin/admin-ajax.php';
    var loading = false;
    var $content = $('.row-forum');
    var currentPage = 1;
    var nextPage; 

    $(document).on('click', '.pagination .next', function (e) {
        e.preventDefault();

        if (loading) return;
        loading = true;

        var nextPage = currentPage + 1;

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'load_more_topics',
                page: nextPage,
                number: 10,
            },
            success: function (response) {
                console.log(response);
                if (response !== 'no_more') {
                    $content.html(response);
                    currentPage = nextPage;
                    updatePaginationButtons();
                } else {
                    // Se não houver mais resultados, desabilite o botão de próxima, mas mantenha o botão de anterior habilitado
                    $('.pagination .next').prop('disabled', true);
                }
                loading = false;
            },
            error: function (error) {
                console.error('Error:', error);
                loading = false;
            }
        });
    });

    $(document).on('click', '.pagination .prev', function (e) {
        e.preventDefault();

        if (loading || currentPage <= 1) return;
        loading = true;

        var prevPage = currentPage - 1;

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'load_more_topics',
                page: prevPage,
                number: 10,
            },
            success: function (response) {
                console.log(response);
                if (response !== 'no_more') {
                    $content.html(response);
                    currentPage = prevPage;
                    updatePaginationButtons();
                }
                loading = false;
            },
            error: function (error) {
                console.error('Error:', error);
                loading = false;
            }
        });
    });

    function updatePaginationButtons() {
        $('.pagination .prev').prop('disabled', currentPage <= 1);
        // Habilitar o botão de próxima quando atualizarmos o conteúdo
        $('.pagination .next').prop('disabled', false);
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const launchList = document.querySelector('.launch-list');
    const launchCards = launchList.querySelectorAll('.launch-card');

    if (launchCards.length > 4) {
        launchList.parentNode.classList.add('show-after-effect');
    }

    launchList.addEventListener('scroll', () => {
        if (launchList.scrollWidth - launchList.offsetWidth - launchList.scrollLeft < 50) {
            // Se estiver perto do final, esconda o efeito
            launchListWrapper.style.setProperty('--after-opacity', '0');
        } else {
            launchListWrapper.style.setProperty('--after-opacity', '0.75');
        }
    });

    let isDown = false;
    let startX;
    let scrollLeft;

    launchList.addEventListener('mousedown', (e) => {
        isDown = true;
        startX = e.pageX - launchList.offsetLeft;
        scrollLeft = launchList.scrollLeft;
    });

    launchList.addEventListener('mouseleave', () => {
        isDown = false;
    });

    launchList.addEventListener('mouseup', () => {
        isDown = false;
    });

    launchList.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - launchList.offsetLeft;
        const walk = (x - startX) * 3; // Ajuste este multiplicador conforme necessário
        launchList.scrollLeft = scrollLeft - walk;
    });
});

