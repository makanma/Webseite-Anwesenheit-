function formatDate(d) {
    d = d + '';
    var str = d.split('-');
    var result = '' + str[2] + '.' + str[1] + '.' + str[0];
    return result;
}


$(window).on('load', function () {
    $("input[type=checkbox]").on("click", function () {

        let daten = $(this).attr("value");

        $.post("index.php",
            {
                checkbox: daten
            })


    })


    $("#personenlist li").on("click", function () {
        let fields = $(this).attr("value").split("&");
        $("#nameID").val(fields[0]);
        $("#vnameInput").val(fields[1]);
        $("#nnameInput").val(fields[2]);
    })

    $("#updateButton").on("click", function () {
        //let daten = $('#nameID').val() + "&" + $('#vnameInput').val() + "&" + $('#nnameInput').val();
        
        //let personlistId = $( "li[personid='"+$('#nameID').val()+"']" ).attr('id');
        //let personlistPersonId = $( "li[personid='"+$('#nameID').val()+"']" ).attr('personid');

        var formData = {
            'action': 'update',
            'id' : $('#nameID').val(),
            'firstName': $.trim($('#vnameInput').val()),
            'lastName': $.trim($('#nnameInput').val())
        };

        $.ajax({
            type: 'POST', // (POST for our form)
            url: 'menu.php', // the url where we want to POST
            data: formData, // ou	r data object
            dataType: 'text', // what type of data do we expect back from server
            encode: true
        })
            // using the done promise callback
            .done(function (data) {

                // copy field values to list item attr
                
                let personlistItem = $( "li[personid='"+$('#nameID').val()+"']" );
                
                let newVal =$('#nameID').val()+"&"+$('#vnameInput').val()+"&"+$('#nnameInput').val();
                let newText =$('#vnameInput').val()+" "+$('#nnameInput').val();

                $(personlistItem).attr('value', newVal );
                $(personlistItem).html(newText);
                
                ;
                
            })
            .fail(function (data) {
                console.log("Fail: "+data);
            });
       
    })

    $("#removeEntry").on("click", function () {

        let confirm = window.confirm("Do you really want to remove the entry? It can not be undone!");
        if (confirm) {

            var formData = {
                'action': 'delete',
                'id' : $('#nameID').val(),
                'firstName': $('#vnameInput').val(),
                'lastName': $('#nnameInput').val()
            };
        
    
            $.ajax({
                type: 'POST', // (POST for our form)
                url: 'menu.php', // the url where we want to POST
                data: formData, // ou	r data object
                dataType: 'text', // what type of data do we expect back from server
                encode: true
            })
                // using the done promise callback
                .done(function (data) {
                    let personlistItem = $( "li[personid='"+$('#nameID').val()+"']" );
                    $(personlistItem).remove();
                    
                })
                .fail(function (data) {
                    console.log("Fail: "+data);
                });
        }
    })
    $("#createbutton").on("click", function () {

        if ($.trim($('#firstNameCreate').val()) == "" || $.trim($('#lastnameCreate').val()) == "") {
            alert("Please enter a name!");
        } else {
            let confirm = window.confirm("Do you really want to create the entry?");
            if (confirm) {

               
                var formData = {
                    'action': 'create',
                    'vorname': $.trim($('#firstNameCreate').val()),
                    'nachname': $.trim($('#lastnameCreate').val())
                };

                $.ajax({
                    type: 'POST', // (POST for our form)
                    url: 'menu.php', // the url where we want to POST
                    data: formData, // ou	r data object
                    dataType: 'text', // what type of data do we expect back from server
                    encode: true
                })
                    // using the done promise callback
                    .done(function (data) {
                        console.log(data);
                        $('#personenlist').append(data);
                    })
                    .fail(function (data) {
                        console.log(data);
                    });



            }
        }

    })
});
