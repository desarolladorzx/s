$(document).on("ready", init); // Inciamos el jquery

function init() {
  Listar();
  function Listar() {
    $.getJSON("./ajax/CatalogoAjax.php?op=Listar", function (r) {
      console.log(r);
      if (r) {
        r.map((e) => {
          $("#container_catalogo").append(`
            <div class='col-sm-3  col-lg-3 col-mg-12 col-md-3' >
            
                <div class="box box-primary" style="min-height:400px">
                    <div class="box-header with-border">
                        <h5 class="">${e.marca}</h5>
                        <div class="box-tools pull-right">
                            <button button class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>

                            <button class="btn btn-box-tool" data-widget="remove">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>

                    
                    <div class="box-body">
                    <span class="mailbox-attachment-icon has-img">
                        <img src="./Files/Voucher/${e.imagen}">
                    </span>
                    <div class="mailbox-attachment-info" style="text-align:center">
                    ${e.nombre}
                    </div>
                </div>
            </div>
            `);
        });

        //         $("#container_catalogo").html(`
        //         <div class="box">
        //             <div class="box-header with-border">
        //                 <h1 class="box-title">Catalogo</h1>
        //                 <div class="box-tools pull-right">
        //                 <button button class="btn btn-box-tool" data-widget="collapse">
        //                     <i class="fa fa-minus"></i>
        //                 </button>

        //                 <button class="btn btn-box-tool" data-widget="remove">
        //                     <i class="fa fa-times"></i>
        //                 </button>
        //             </div>
        //         </div>

        // `);
      }
    });
  }
}
