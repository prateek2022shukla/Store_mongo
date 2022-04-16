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


$("#variations").click(function (e) {
    e.preventDefault();
    var html = '';
    html += '<div id="inputFormRow">';
    html += '<div class="input-group mb-3">';
    html += '<input type="text" name="variation_field[]" class="form-control m-input" placeholder="Field" autocomplete="off">';
    html += '<input type="text" name="variation_name[]" class="form-control m-input" placeholder="Vaule" autocomplete="off">'; 
    html += '<input type="text" name="variation_price[]" class="form-control m-input" placeholder="Price" autocomplete="off">';
    html += '<div class="input-group-append">';
    html += '<button id="removeRow" type="button" class="btn btn-danger">x</button>';
    html += '</div>';
    html += '</div>';
    html +='</div>';

    $('#new_variation').append(html);
});


//Create div is deleted when clicked on X button
$(document).on("click", "button#removeRow" , function() {    
    $(this).parent().parent().remove();
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

/**
 * Function to display one product details in Popup window 
 * @param {} data 
 */
function oneProduct(data)
{
    additionals = data[0];
    variations = data[1];
        
    if (typeof(additionals) != 'undefined') {
        label = additionals['label'];
        value = additionals['value'];
    }
    
    if( variations) {
        variation_field = variations['Variation Field'];
        variation_name = variations['Variation Name'];
        variation_price = variations['Variation Price'];
    }
    
  
    
    var html = '';
    html += '<ul>';
    html += '<li> Product Name : '+data['name']+' </li>';
    html += '<li> Product Catergory : '+data['category']+' </li>';
    html += '<li> Product Price : '+data['price']+' </li>';
    html += '<li> Product Stocks : '+data['stock']+' </li>';
    html += '<ul>';
    if (typeof(label) != 'undefined' && typeof(value) != 'undefined') {
        html += '<h4>Additional Information</h4>'
        for (var i=0;i<label.length;i++) {
            html += label[i] + " : " + value[i]+"<br>";
        }
    }
    if (typeof(variation_field) != 'undefined' && typeof(variation_name) != 'undefined'  && typeof(variation_price) != 'undefined') {
        html += '<h4>Variations</h4>'
        for (var i=0;i<variation_field.length;i++) {
            html += variation_field[i] + " : " + variation_name[i]+" : " + variation_price[i]+" ₹<br>";
        }
    }
    $('#data').html(html);
}


/**
 * Function of search product using Name and Id
 */
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
 