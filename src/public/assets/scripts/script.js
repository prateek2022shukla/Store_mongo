//New div is created when clicked on addition field button
$("#additional_fields").click(function (e) {
    e.preventDefault();
    var html = '';
    html += '<div id="inputFormRow">';
    html += '<div class="input-group mb-3">';
    html += '<input type="text" name="label[]" class="form-control m-input" placeholder="Label" autocomplete="off">';
    html += '<input type="text" name="value[]" class="form-control m-input" placeholder="Value" autocomplete="off">';
    html += '<div class="input-group-append">';
    html += '<button id="removeRow" type="button" class="btn btn-danger">x</button>';
    html += '</div>';
    html += '</div>';
    html +='</div>';

    $('#new').append(html);
});

//Create div is deleted when clicked on X button
$(document).on("click", "button#removeRow" , function() {    
    $(this).parent().parent().remove();
});

//Hiding variation section by default
$('#new_variation').hide();


//Variation section is toggeled when clicked on variation button
$("#variations").click(function(){
    $("#new_variation").toggle();
});


//popup div is hidden by default
$('#popup').hide();

//Popup section is toggeled when clicked on view more button
$(document).on("click", "input#show_popup" , function(e) {
    $("#popup").toggle();
    e.preventDefault();
});

//Popup section hides when clicked on X button
$("#close").click(function(e){
    $("#popup").hide();
    e.preventDefault();
});


$(document).ready(function(){
    $(document).on('click', '#show_popup',function(){         
     $.ajax(
         {
         'url': '/products/oneproduct',
         'method':'POST',
         'data' : {'product_id':$(this).data('id')},
         'datatype' : 'JSON'
     }).done(function(data){     
         jData = JSON.parse(data);      
         oneProduct(jData);          
     });
 }); 
});

function oneProduct(data)
{
    additionals = data[0];
    variations = data[1];
        
    if (typeof(additionals) != 'undefined') {
        label = additionals['label'];
        value = additionals['value'];
    }
    

    if (typeof(variations) != 'undefined') 
    {    
        color = variations['color'];
        size = variations['size'];
    }

 
    
    var html = '';
    html += '<ul>';
    html += '<li> Product Name : '+data['name']+' </li>';
    html += '<li> Product Catergory : '+data['category']+' </li>';
    html += '<li> Product Price : '+data['price']+' </li>';
    html += '<li> Product Stocks : '+data['stock']+' </li>';
    html += '<ul>';
    if (typeof(label) != 'undefined' && typeof(variations) != 'undefined') {
        html += '<h4>Additional Information</h4>'
        for (var i=0;i<label.length;i++) {
            html += label[i] + " : " + value[i]+"<br>";
        }
    }
    if (typeof(color) != 'undefined' && typeof(size) != 'undefined') {
    html += '<h4>Variations</h4>'
    html += 'Color : '+color+"<br>";
    html += 'Size : '+size;
    }
    $('#data').html(html);
}


function search() {
    var input, filter, table, tr, td1, td2, i, txtValue1, txtValue2;
    input = document.getElementById("searchValue");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
      td1 = tr[i].getElementsByTagName("td")[0];
      td2 = tr[i].getElementsByTagName("td")[1];
      if (td1 || td2) {
        txtValue1 = td1.textContent || td1.innerText;
        txtValue2 = td2.textContent || td2.innerText;
        if (txtValue1.toUpperCase().indexOf(filter) > -1 || txtValue2.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }       
    }
  }
 