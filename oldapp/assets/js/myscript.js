// Swal.fire({
// 	title: "Tes SweetAlert2",
// 	text: "Tes SweetAlert2",
// 	type: "success",
// 	icon: "success",
// });
var baseUrl = "http://localhost/ci3ukk/";

const flashData = $(".flash-data-kategori").data("flashdata");
const flashDataBarang = $(".flash-data-barang").data("flashdata");

// console.log(flashData);
// Swal.fire("Tes", "Tes " + flashData, "success");

if (flashDataBarang == "Ditambahkan") {
	Swal.fire({
		title: "Data " + flashDataBarang,
		text: "Data telah berhasil " + flashDataBarang,
		type: "success",
		icon: "success",
	});
}

if (flashDataBarang == "Diubah") {
	Swal.fire({
		title: "Data " + flashDataBarang,
		text: "Data telah berhasil " + flashDataBarang,
		type: "success",
		icon: "success",
	});
}

if (flashDataBarang == "Dihapus") {
	Swal.fire({
		title: "Data " + flashDataBarang,
		text: "Data telah berhasil " + flashDataBarang,
		type: "success",
		icon: "success",
	});
}

if (flashData == "Ditambahkan") {
	Swal.fire({
		title: "Data " + flashData,
		text: "Data telah berhasil " + flashData,
		type: "success",
		icon: "success",
	});
}

if (flashData == "Diubah") {
	Swal.fire({
		title: "Data " + flashData,
		text: "Data telah berhasil " + flashData,
		type: "success",
		icon: "success",
	});
}

if (flashData == "Dihapus") {
	Swal.fire({
		title: "Data " + flashData,
		text: "Data telah berhasil " + flashData,
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
		text: "Data Kategori akan dihapus",
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
