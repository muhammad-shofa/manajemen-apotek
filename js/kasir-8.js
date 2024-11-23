// Fungsi untuk menambah barang ke keranjang
function tambahKeKeranjang(barangId, namaBarang, hargaSatuan, hargaButir) {
  const keranjangContainer = document.getElementById("keranjangContainer");

  // Ambil data keranjang yang sudah disimpan di localStorage, jika ada
  let keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];

  // Cek apakah barang sudah ada di keranjang
  const existingItem = keranjang.find((item) => item.id === barangId);
  
  if (existingItem) {
    // Jika barang sudah ada, perbarui jumlah dan total harga
    existingItem.jumlahKeluar += 1;

    // Simpan kembali ke localStorage
    localStorage.setItem("keranjang", JSON.stringify(keranjang));

    // Update tampilan jumlah dan total harga di UI
    const jumlahElement = document.querySelector(
      `#item-${barangId} .jumlahKeluar`
    );
    const totalHargaElement = document.querySelector(
      `#item-${barangId} .totalHarga`
    );

    jumlahElement.value = existingItem.jumlahKeluar;
    totalHargaElement.innerText =
      "Rp " + existingItem.jumlahKeluar * hargaSatuan;
  } else {
    // Jika barang belum ada di keranjang, buat item baru
    const keranjangItem = {
      id: barangId,
      namaBarang: namaBarang,
      hargaSatuan: hargaSatuan,
      hargaButir: hargaButir,
      jumlahKeluar: 1,
    };

    keranjang.push(keranjangItem);

    // Simpan ke localStorage
    localStorage.setItem("keranjang", JSON.stringify(keranjang));

    // Buat elemen baru untuk barang di keranjang
    const keranjangDiv = document.createElement("div");
    keranjangDiv.classList.add("card", "w-100", "d-flex", "p-2", "m-1");
    keranjangDiv.id = `item-${barangId}`;
    keranjangDiv.setAttribute("data-id", barangId); // Set data-id untuk item

    keranjangDiv.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="text-dark">${namaBarang}</h5>
          <div class="d-flex align-items-center">
            <button class="btn btn-sm btn-outline-secondary me-2" onclick="kurangiJumlah('${barangId}', ${hargaSatuan})">
              <i class="fas fa-minus"></i>
            </button>
            <input type="number" style="max-width: 80px;" class="jumlahKeluar form-control mx-2" value="1" min="1"
                   oninput="updateJumlah('${barangId}', ${hargaSatuan}, this.value)" 
                   onblur="handleBlur('${barangId}', ${hargaSatuan}, this)">
            <button class="btn btn-sm btn-outline-secondary ms-2" onclick="tambahKeKeranjang('${barangId}', '${namaBarang}', ${hargaSatuan}, ${hargaButir})">
              <i class="fas fa-plus"></i>
            </button>
          </div>
          <p>Rp ${hargaSatuan}</p>
          <p>Rp ${hargaButir}</p>
          <p class="totalHarga">Rp ${hargaSatuan}</p>
          <button class="btn btn-sm btn-outline-danger ms-2" onclick="hapusBarangDariKeranjang('${barangId}')">
            <i class="fas fa-trash"></i>
          </button>
        </div>`;

    keranjangContainer.appendChild(keranjangDiv);
  }

  // Update total harga semua barang di keranjang
  updateTotalHarga();
}

// Fungsi untuk mengurangi jumlah barang di keranjang
function kurangiJumlah(barangId, hargaSatuan) {
  // Ambil data keranjang dari localStorage
  let keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];

  // Temukan barang di keranjang
  const existingItem = keranjang.find((item) => item.id === barangId);

  if (existingItem && existingItem.jumlahKeluar > 1) {
    // Kurangi jumlah barang
    existingItem.jumlahKeluar -= 1;

    // Simpan kembali ke localStorage
    localStorage.setItem("keranjang", JSON.stringify(keranjang));

    // Update tampilan jumlah dan total harga
    const jumlahElement = document.querySelector(
      `#item-${barangId} .jumlahKeluar`
    );
    const totalHargaElement = document.querySelector(
      `#item-${barangId} .totalHarga`
    );

    jumlahElement.value = existingItem.jumlahKeluar;
    totalHargaElement.innerText =
      "Rp " + existingItem.jumlahKeluar * hargaSatuan;
  } else {
    // Jika jumlah barang sudah 1, bisa dihapus
    hapusBarangDariKeranjang(barangId);
  }

  // Update total harga semua barang di keranjang
  updateTotalHarga();
}

// Fungsi untuk mengupdate jumlah barang berdasarkan input
function updateJumlah(barangId, hargaSatuan, jumlahBaru) {
  // Ambil data keranjang dari localStorage
  let keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];

  const existingItem = keranjang.find((item) => item.id === barangId);

  if (existingItem) {
    // Validasi input jumlah
    let jumlah = parseInt(jumlahBaru);

    if (isNaN(jumlah) || jumlah <= 0) {
      jumlah = 1; // Default ke 1 jika input tidak valid
    }

    existingItem.jumlahKeluar = jumlah;

    // Simpan kembali ke localStorage
    localStorage.setItem("keranjang", JSON.stringify(keranjang));

    // Update tampilan jumlah dan total harga
    const totalHargaElement = document.querySelector(
      `#item-${barangId} .totalHarga`
    );
    totalHargaElement.innerText =
      "Rp " + existingItem.jumlahKeluar * hargaSatuan;
  }

  // Update total harga semua barang di keranjang
  updateTotalHarga();
}

// Fungsi untuk menangani blur input jumlah
function handleBlur(barangId, hargaSatuan, inputElement) {
  if (inputElement.value === "" || parseInt(inputElement.value) <= 0) {
    // Jika input kosong atau tidak valid, set ke 1
    inputElement.value = 1;
    updateJumlah(barangId, hargaSatuan, inputElement.value);
  }
}

// Fungsi untuk menghitung dan menampilkan total harga keranjang
function updateTotalHarga() {
  // Ambil data keranjang dari localStorage
  const keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];
  const totalHargaElement = document.getElementById("totalHargaSemuaBarang");

  let totalHarga = keranjang.reduce(
    (total, item) => total + item.hargaSatuan * item.jumlahKeluar,
    0
  );

  totalHargaElement.innerText = "Total: Rp " + totalHarga;
}

// Fungsi untuk menghapus barang dari keranjang
function hapusBarangDariKeranjang(barangId) {
  // Ambil data keranjang dari localStorage
  let keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];

  // Hapus item dari array keranjang
  keranjang = keranjang.filter((item) => item.id !== barangId);

  // Simpan kembali ke localStorage
  localStorage.setItem("keranjang", JSON.stringify(keranjang));

  // Hapus elemen barang dari UI
  const itemElement = document.getElementById(`item-${barangId}`);
  if (itemElement) {
    itemElement.remove();
  }

  // Update total harga semua barang di keranjang
  updateTotalHarga();
}

// Fungsi untuk menampilkan keranjang yang ada di localStorage
function tampilkanKeranjang() {
  const keranjangContainer = document.getElementById("keranjangContainer");

  // Ambil data keranjang dari localStorage
  const keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];

  // Kosongkan keranjang container sebelum menambah data
  keranjangContainer.innerHTML = "";

  // Jika keranjang tidak kosong, tampilkan setiap barang
  keranjang.forEach((item) => {
    const keranjangItem = document.createElement("div");
    keranjangItem.classList.add("card", "w-100", "d-flex", "p-2", "m-1");
    keranjangItem.id = `item-${item.id}`;
    keranjangItem.setAttribute("data-id", item.id);

    keranjangItem.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="text-dark">${item.namaBarang}</h5>
          <div class="d-flex align-items-center">
            <button class="btn btn-sm btn-outline-secondary me-2" onclick="kurangiJumlah('${
              item.id
            }', ${item.hargaSatuan})">
              <i class="fas fa-minus"></i>
            </button>
            <input type="number" style="max-width: 80px;" class="jumlahKeluar form-control mx-2" value="${
              item.jumlahKeluar
            }" min="1"
                   oninput="updateJumlah('${item.id}', ${
      item.hargaSatuan
    }, this.value)" 
                   onblur="handleBlur('${item.id}', ${item.hargaSatuan}, this)">
            <button class="btn btn-sm btn-outline-secondary ms-2" onclick="tambahKeKeranjang('${
              item.id
            }', '${item.namaBarang}', ${item.hargaSatuan}, ${item.hargaButir})">
              <i class="fas fa-plus"></i>
            </button>
          </div>
          <p>Rp ${item.hargaSatuan}</p>
          <p>Rp ${item.hargaButir}</p>
          <p class="totalHarga">Rp ${item.hargaSatuan * item.jumlahKeluar}</p>
          <button class="btn btn-sm btn-outline-danger ms-2" onclick="hapusBarangDariKeranjang('${
            item.id
          }')">
            <i class="fas fa-trash"></i>
          </button>
        </div>`;

    keranjangContainer.appendChild(keranjangItem);
  });

  // Update total harga setelah menampilkan barang
  updateTotalHarga();
}

// Panggil tampilkanKeranjang ketika halaman dimuat untuk menampilkan data keranjang yang sudah ada
document.addEventListener("DOMContentLoaded", tampilkanKeranjang);
