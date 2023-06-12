$(document).on("ready", init); // Inciamos el jquery

function init() {
  ListadoRegistroActivad();
  function ListadoRegistroActivad() {
    var tabla = $("#tblRegistroActividad")
      .dataTable({
        aProcessing: true,
        "pageLength": 20
        ,
        aServerSide: true,
        dom: "Bfrtip",
        buttons: ["excelHtml5"],
        aoColumns: [
          { mDataProp: "0" },
          { mDataProp: "1" },
          { mDataProp: "2" },
          { mDataProp: "3" },
          { mDataProp: "4" },
          { mDataProp: "5" },
          { mDataProp: "6" },
          { mDataProp: "7" },
          { mDataProp: "8", visible: false },
        ],
        ajax: {
          url: "./ajax/RegistroActividadAjax.php?op=list",
          type: "get",
          dataType: "json",

          error: function (e) {
            console.log(e.responseText);
          },
        },
        bDestroy: true,
        stripeClasses: [], 
        stripeOdd: false,
        
        rowCallback: function (row, data) {
          var col6Value = data[6]; // Obtener el valor de la columna 6

          if (col6Value === "A") {
            $(row).addClass("bg-success"); // Aplicar clase de Bootstrap para color verde
          } else {
            $(row).addClass("bg-danger"); // Aplicar clase de Bootstrap para color rojo
          }
        },
      })
      .DataTable();
  }
}
