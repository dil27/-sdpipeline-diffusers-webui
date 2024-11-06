$(document).ready(function() {
    $('.no-runtime').hide();
    let runtimeUrl = $('#runtime-url').val()
    if (runtimeUrl[runtimeUrl.length - 1] == "/") {
        runtimeUrl = runtimeUrl.slice(0, -1);
    }
    
    
    if (window.location.pathname == "/generate") {
        var settings = {
            "url": `${runtimeUrl}/checkpipe`,
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            }
        };
        
        $.ajax(settings).done(function (response) {
            if(response.error) {
                $('.preloader').hide();
                $('.no-runtime').show();
            } else {
                if (response.pipe !== null) {
                    $('.preloader').hide();
                    $('.no-runtime').hide();
                    $('#checkpoint').text(`Checkpoint: ${response.pipe}`);
                    $('#checkpoint-name').val(response.pipe);
                    $('main.container.box').css('display', 'block');
                } else {
                    $('.preloader').hide();
                    $('.no-runtime').show();
                }
            }
        });
    }
    
    $('.add-checkpoint-group').hide();
    let notifNumber = 0;
    popNotif(notifNumber, "Connected", "Runtime connected.")

    let currentVersion = null;
    $.get('appversion.txt', function(data) {
        currentVersion = data;
        popNotif(notifNumber, "Update available", "Update available. Please check our github repository.")
    }).fail(function() {
        popNotif(notifNumber, "Update available", "Update available. Please check our github repository.")
    });

    $('#checkpoint').on('click', function() {
        if ($('.modal').hasClass('open')) {
            $('.modal').removeClass('open');
        } else {
            $('.modal').addClass('open');
        }
    });

    $('.modal-close').on('click', function() {
        $('.modal').removeClass('open');
    });

    $('input[type="range"]').on('input', function() {
        const id = $(this).attr('id');
        $(`#value-${id}`).text($(this).val())
    });

    $('#checkpoint-search').on('keyup', function() {
        let searchTerm = $(this).val().toLowerCase();

        $('.checkpoint-group').each(function() {
            let checkpointPath = $(this).find('.checkpoint-path').text().toLowerCase();

            if (checkpointPath.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });

        if (searchTerm.length > 0) {
            $('.add-checkpoint-group').show();
            $('.add-checkpoint-group').attr('data-checkpoint', searchTerm);
            $('.add-checkpoint-group .checkpoint-detail .checkpoint-name').text(searchTerm);
            $('.add-checkpoint-group .checkpoint-detail .checkpoint-path').text(searchTerm);
            $('.add-checkpoint-group .checkpoint-detail .checkpoint-path').attr('href', `https://huggingface.co/${searchTerm}`);
        } else {
            $('.add-checkpoint-group').hide();
        }
    });

    $('.checkpoint-load.btn').on('click', function() {
        popNotif(notifNumber, "Processing", "Loading checkpoint. Please wait...")
        const selectedCheckpoint = $(this).parent().attr('data-checkpoint');
        let checkpointName = selectedCheckpoint.split('/')[1]

        $('.modal').removeClass('open');
        $('.no-runtime').hide();
        $('.preloader').show();
        $('main.container.box').css('display', 'none');

        $('#checkpoint').text(`Loading: ${checkpointName}`)

        var settings = {
            "url": `/api/loadcheckpoint`,
            "method": "POST",
            "timeout": 120000,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "runtimeUrl": runtimeUrl,
                "checkpoint": selectedCheckpoint
            }),
        };
          
        $.ajax(settings).done(function (response) {
            if (response.checkpoint) {
                $('#checkpoint-name').val(response.checkpoint);
                $('#checkpoint').text(`Checkpoint: ${checkpointName}`);
                $('.preloader').hide();
                $('main.container.box').css('display', 'block');
                popNotif(notifNumber, "Success", "Checkpoint Loaded")
            } else {
                popNotif(notifNumber, "Error", "Failed to load checkpoint. Please try again")
            }
        });
    });

    $('.checkpoint-add.btn').on('click', function() {
        popNotif(notifNumber, "Processing", "Loading checkpoint. Please wait...")
        const selectedCheckpoint = $(this).parent().attr('data-checkpoint');
        let checkpointName = selectedCheckpoint.split('/')[1]

        $('.modal').removeClass('open');
        $('.no-runtime').hide();
        $('.preloader').show();
        $('main.container.box').css('display', 'none');

        $('#checkpoint').text(`Loading: ${checkpointName}`)

        var settings = {
            "url": `/api/addcheckpoint`,
            "method": "POST",
            "timeout": 400000,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "runtimeUrl": runtimeUrl,
                "checkpoint": selectedCheckpoint
            }),
        };
          
        $.ajax(settings).done(function (response) {
            if (response.checkpoint) {
                $('#checkpoint-name').val(response.checkpoint);
                $('#checkpoint').text(`Checkpoint: ${checkpointName}`);
                $('.preloader').hide();
                $('main.container.box').css('display', 'block');
                popNotif(notifNumber, "Success", "Checkpoint Loaded")
            } else {
                $('.preloader').hide();
                $('.modal').addClass('open');
                popNotif(notifNumber, "Error", response.msg)
            }
        });
    });

    $('.generate.btn').on('click', function() {
        if ($('#checkpoint-name').val().length < 1) return;

        let seed = $('#seed').val();
        if (seed == "-1") {
            seed = Math.floor(Math.random() * (2 ** 32));
        }

        var settings = {
            "url": `${runtimeUrl}/txt2img`,
            "method": "POST",
            "timeout": 0,
            "headers": {
              "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                checkpoint: $('#checkpoint-name').val(),
                prompt: $('#prompt').val(),
                neg: $('#negative-prompt').val(),
                seed: parseFloat(seed),
                width: parseFloat($('#width').val()),
                height: parseFloat($('#height').val()),
                sampling: parseFloat($('#sampling-step').val()),
                guidance: parseFloat($('#cfg-scale').val())
            }),
        };
          
        $.ajax(settings).done(function (response) {
            if (response.image) {
                const result = `data:image/png;base64,${response.image}`
                $('.generation-result').attr('src', result);
                $('#seed-result').val(response.data.seed)
                popNotif(notifNumber, "Success", "Image generated")
            }
        });
    });

    $('.save-image.btn').on('click', function() {
        const imageUrl = $('.generation-result').attr('src');

        var d = new Date();

        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const year = String(d.getFullYear()).slice(-2);
        const hours = String(d.getHours()).padStart(2, '0');
        const minutes = String(d.getMinutes()).padStart(2, '0');

        const formattedDateTime = `${day}${month}${year}${hours}${minutes}`;
        let filename = `ZRDiffusion_${$('#seed-result').val()}-${formattedDateTime}.png`;

        const link = document.createElement('a');
        link.href = imageUrl;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    /**
     * 
     * @param {Variable} notifId use notifNumber
     * @param {String} title title of notification
     * @param {String} msg message of notification
     */
    function popNotif(notifId = notifNumber, title, msg) {
        notifNumber = notifId + 1;
        $('.notification-area').append(`
            <div class="notification" id="notif-${notifId}">
                <div class="title" id="notif-title">${title}</div>
                <div class="msg" id="notif-msg">${msg}</div>
            </div>
        `);

        setTimeout(function() {
            $(`#notif-${notifId}`).animate({
                opacity: 0
            }, 500, function() {
                $(`#notif-${notifId}`).remove();
            });
        }, 10000)
    }
})