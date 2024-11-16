function tambahKeKeranjang(barangId, namaBarang, hargaSatuan) {
  const keranjangContainer = document.getElementById("keranjangContainer");

  const existingItem = Array.from(keranjangContainer.children).find(
    (item) => item.getAttribute("data-id") === barangId
  );

  if (existingItem) {
    // Jika ada, perbarui jumlah dan total harga
    const jumlahElement = existingItem.querySelector(".jumlahKeluar");
    const totalHargaElement = existingItem.querySelector(".totalHarga");
    const jumlahKeluar = parseInt(
      existingItem.getAttribute("data-jumlah-keluar")
    ); // Ambil jumlah keluar dari atribut

    let jumlah = jumlahKeluar + 1;
    existingItem.setAttribute("data-jumlah-keluar", jumlah); // Update data-jumlah-keluar
    jumlahElement.innerText = jumlah; // Update tampilan jumlah

    const totalHarga = jumlah * hargaSatuan;
    totalHargaElement.innerText = "Rp " + totalHarga;
  } else {
    // Jika belum ada, buat item baru di keranjang
    const keranjangItem = document.createElement("div");
    keranjangItem.classList.add("card", "w-100", "d-flex", "p-2", "m-1");
    keranjangItem.setAttribute("data-id", barangId);
    keranjangItem.setAttribute("data-jumlah-keluar", 1); // Set jumlah keluar awal 1

    keranjangItem.innerHTML = `
      <div class="d-flex justify-content-between align-items-center">
          <h5 class="text-dark">${namaBarang}</h5>
          <div class="d-flex align-items-center">
              <button class="btn btn-sm btn-outline-secondary me-2" onclick="kurangiJumlah('${barangId}', ${hargaSatuan})">
                  <i class="fas fa-minus"></i>
              </button>
              <p class="jumlahKeluar m-0 mx-2"></p>
              <button class="btn btn-sm btn-outline-secondary ms-2" onclick="tambahKeKeranjang('${barangId}', '${namaBarang}', ${hargaSatuan})">
                  <i class="fas fa-plus"></i>
              </button>
          </div>
          <p>Rp ${hargaSatuan}</p>
          <p class="totalHarga">Rp ${hargaSatuan}</p>
      </div>`;
    // <p class="jumlahKeluar m-0 mx-2"></p> // JUMLAH KELUAR OLD
    // <input class="jumlahKeluar" name="jumlahKeluar" />

    keranjangContainer.appendChild(keranjangItem);
  }

  // Update total harga semua barang di keranjang
  updateTotalHarga();
}

function kurangiJumlah(barangId, hargaSatuan) {
  const keranjangContainer = document.getElementById("keranjangContainer");
  const existingItem = Array.from(keranjangContainer.children).find(
    (item) => item.getAttribute("data-id") === barangId
  );

  if (existingItem) {
    const jumlahElement = existingItem.querySelector(".jumlahKeluar");
    const totalHargaElement = existingItem.querySelector(".totalHarga");
    const jumlahKeluar = parseInt(
      existingItem.getAttribute("data-jumlah-keluar")
    ); // Ambil jumlah keluar dari atribut

    let jumlah = jumlahKeluar - 1;

    if (jumlah <= 0) {
      // Hapus item jika jumlah 0
      keranjangContainer.removeChild(existingItem);
    } else {
      // Update jumlah dan total harga
      existingItem.setAttribute("data-jumlah-keluar", jumlah); // Update data-jumlah-keluar
      jumlahElement.innerText = jumlah;
      const totalHarga = jumlah * hargaSatuan;
      totalHargaElement.innerText = "Rp " + totalHarga;
    }
  }

  // Update total harga semua barang di keranjang
  updateTotalHarga();
}

// Fungsi untuk menghitung total harga semua barang di keranjang
function updateTotalHarga() {
  const keranjangContainer = document.getElementById("keranjangContainer");
  const totalHargaSemuaBarangElement = document.getElementById(
    "totalHargaSemuaBarang"
  ); // Elemen untuk menampilkan total harga semua barang
  let totalHarga = 0;

  // Loop melalui setiap item di keranjang dan tambahkan harga totalnya
  Array.from(keranjangContainer.children).forEach((item) => {
    const totalHargaItem = parseInt(
      item.querySelector(".totalHarga").innerText.replace("Rp ", "")
    );
    totalHarga += totalHargaItem;
  });

  // Tampilkan total harga semua barang
  totalHargaSemuaBarangElement.innerText = "Rp " + totalHarga;
}
// });
