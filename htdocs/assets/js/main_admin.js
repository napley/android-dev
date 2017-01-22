/*!
 * Main admin JS
 */
$(document).ready(function () {
    CKEDITOR.dtd.$removeEmpty['span'] = false;
    
    $("<input type='button' value='Effacer' onclick='emptyPrevInput(this)'/>").insertAfter("form input[data-type='elfinder-input-field']");
    
    $('.table').DataTable({
        "order": [[ 0, "desc" ]],
        "lengthMenu": [[100, -1], [100, "All"]],
        "columnDefs": [
            { "type": "num", "targets": 1 }
          ]
    });
    
    $("#listeMotCle input").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: $("#listeMotCle").data('url'),
                dataType: "json",
                type:"POST",
                data: {
                    q: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        minLength: 1,
        select: function (event, ui) {
            this.value = ui.item.label;
        },
        open: function () {
            $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
        },
        close: function () {
            $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
        }
    });
   
});

function addMotCle() {
    var $listeMotCle = $("#listeMotCle");

    $listeMotCle.append('<li><input class="indexPartProject" type="text" name="motCle[]" />'
            + '<button onclick="delMotCle(this);">x</button></li>');
    
    $("#listeMotCle input").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: $("#listeMotCle").data('url'),
                dataType: "json",
                type:"POST",
                data: {
                    q: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        minLength: 1,
        select: function (event, ui) {
            this.value = ui.item.label;
        },
        open: function () {
            $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
        },
        close: function () {
            $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
        }
    });
}

function delMotCle(ligneMotCle) {
    ligneMotCle.parentNode.parentNode.removeChild(ligneMotCle.parentNode);
}

function emptyPrevInput(element) {
    $(element).prev('input').val('');
}

function addPartProject() {
    var $listePartProject = $("#listePartProject");

    $listePartProject.append('<li>'
            + '<input type="hidden" name="partProject[id][]" value="' + $("#partProject").val() + '" />'
            + '<label>' + $("#partProject option:selected").text() + '</label>'
            + '<input class="indexPartProject" type="text" name="partProject[index][]" value="' + $("#indexPartProject").val() + '" />'
            + '<label onclick="delPartProject(this);">x</label></li>');
}

function delPartProject(lignePartProject) {
    lignePartProject.parentNode.parentNode.removeChild(lignePartProject.parentNode);
}