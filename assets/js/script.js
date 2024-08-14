const flashData = $(".flash-data").data("flashdata");

// Swal.fire("Tes", "Tes " + flashData, "success");

if (flashData) {
	Swal.fire({
		title: "Data ",
		text: "Berhasil " + flashData,
		type: "success",
		icon: "success",
	});
}

$(".tombol-hapus").on("click", function (e) {
	e.preventDefault();
	const href = $(this).attr("href");
	console.log(href);

	Swal.fire({
		title: "Apakah Anda yakin?",
		text: "Data akan dihapus",
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "Hapus Data",
	}).then((result) => {
		if (result.isConfirmed) {
			document.location.href = href;
		}
	});
});
