$(document).on("ready", init);

function init(){

	if ($("#txtMnuAlmacen").val() == "0") {
		$("#liAlmacen").hide();
	}
	if ($("#txtMnuCompras").val() == "0") {

		$("#liCompras").hide();
	};
	if ($("#txtMnuVentas").val() == "0") {

		$("#liVentas").hide();
	};
	if ($("#txtMnuMantenimiento").val() == "0") {

		$("#liMantenimiento").hide();
	};
	if ($("#txtMnuSeguridad").val() == "0") {

		$("#liSeguridad").hide();
	};
	if ($("#txtMnuConsultaCompras").val() == "0") {

		$("#liConsultaCompras").hide();
	};
	if ($("#txtMnuConsultaVentas").val() == "0") {

		$("#liConsultaVentas").hide();
	};
	if ($("#chkMnuDocEV").val() == "0") {

		$("#liDocsEV").hide();
	};
	if ($("#chkMnuDocJV").val() == "0") {

		$("#liDocsJV").hide();
	};
	if ($("#chkMnuDocJA").val() == "0") {

		$("#liDocsJA").hide();
	};
	if ($("#chkMnuDocJL").val() == "0") {

		$("#liDocsJL").hide();
	};

	// documentacion 

	if ($("#doc-txtMnuDocumentacion").val() == "0") {

		$("#liDocumentacion").hide();
	};
	if ($("#doc-txtMnuDocVentas").val() == "0") {

		$("#liDocVentas").hide();
	};
	if ($("#doc-txtMnuDocMarketing").val() == "0") {

		$("#liDocMarketing").hide();
	};
	if ($("#doc-txtMnuDocLogistica").val() == "0") {

		$("#liDocLogistica").hide();
	};
	if ($("#doc-txtMnuDocFinanzas").val() == "0") {

		$("#liDocFinanzas").hide();
	};
	if ($("#doc-txtMnuDocRRHH").val() == "0") {

		$("#liDocRRHH").hide();
	};
	if ($("#doc-txtMnuDocIT").val() == "0") {

		$("#liDocIT").hide();
	};
	if ($("#doc-txtMnuDoProduccion").val() == "0") {

		$("#liDoProduccion").hide();
	};

	//documentacion
	
	
}