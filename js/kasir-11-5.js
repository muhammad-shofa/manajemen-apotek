function tambahKeKeranjang(barangId, namaBarang, hargaSatuan, hargaButir) {
  const keranjangContainer = document.getElementById("keranjangContainer");

  // Ambil data keranjang yang sudah disimpan di localStorage, jika ada
  let keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];

  // Tentukan harga yang digunakan
  const hargaAktual = hargaSatuan > 0 ? hargaSatuan : hargaButir;

  // Cek apakah barang sudah ada di keranjang
  const existingItem = keranjang.find((item) => item.id === barangId);

  if (existingItem) {
    // Jika barang sudah ada, perbarui jumlah dan total harga
    existingItem.jumlahKeluar += 1;
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
      "Rp " + existingItem.jumlahKeluar * hargaAktual;
  } else {
    // Jika barang belum ada di keranjang, buat item baru
    const keranjangItem = {
      id: barangId,
      namaBarang: namaBarang,
      hargaSatuan: hargaSatuan,
      hargaButir: hargaButir,
      hargaAktual: hargaAktual,
      jumlahKeluar: 1,
    };

    keranjang.push(keranjangItem);
    localStorage.setItem("keranjang", JSON.stringify(keranjang));

    // Buat elemen baru untuk barang di keranjang
    const keranjangDiv = document.createElement("div");
    keranjangDiv.classList.add("card", "w-100", "d-flex", "p-2", "m-1");
    keranjangDiv.id = `item-${barangId}`;
    keranjangDiv.setAttribute("data-id", barangId);

    keranjangDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="text-dark">${namaBarang}</h5>
              <div class="d-flex align-items-center">
                <button class="btn btn-sm btn-outline-secondary me-2" onclick="kurangiJumlah('${barangId}', ${hargaAktual})">
                  <i class="fas fa-minus"></i>
                </button>
                <input type="number" style="max-width: 80px;" class="jumlahKeluar form-control mx-2" value="1" min="1"
                       oninput="updateJumlah('${barangId}', ${hargaAktual}, this.value)" 
                       onblur="handleBlur('${barangId}', ${hargaAktual}, this)">
                <button class="btn btn-sm btn-outline-secondary ms-2" onclick="tambahKeKeranjang('${barangId}', '${namaBarang}', ${hargaSatuan}, ${hargaButir})">
                  <i class="fas fa-plus"></i>
                </button>
              </div>
              ${
                hargaSatuan > 0
                  ? `<p class="hargaSatuan editable">Rp ${hargaSatuan}</p>`
                  : ""
              }
              ${
                hargaButir > 0
                  ? `<p class="hargaButir editable">Rp ${hargaButir}</p>`
                  : ""
              }
              <p class="totalHarga">Rp ${hargaAktual}</p>
              <button class="btn btn-sm btn-outline-danger ms-2" onclick="hapusBarangDariKeranjang('${barangId}')">
                <i class="fas fa-trash"></i>
              </button>
            </div>`;

    keranjangContainer.appendChild(keranjangDiv);
  }

  // Update total harga semua barang di keranjang
  updateTotalHarga();
}

//
//
//
// Fungsi untuk mengganti harga menjadi input yang dapat diedit
function makeEditable(element) {
  const currentValue = element.textContent.trim(); // ambil nilai yang sekarang
  element.innerHTML = `<input type="number" value="${currentValue}" />`; // ganti menjadi input
  const input = element.querySelector("input");

  // Fokuskan ke input agar langsung bisa di-edit
  input.focus();

  // Event untuk menyimpan perubahan saat input kehilangan fokus
  input.addEventListener("blur", function () {
    const newValue = input.value;
    element.innerHTML = newValue; // ganti kembali menjadi teks biasa
    element.dataset.harga = newValue; // simpan harga baru pada atribut data-harga
  });

  // Event untuk menangani enter, bisa langsung mengubah harga saat tekan Enter
  input.addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
      input.blur(); // Trigger blur saat Enter ditekan
    }
  });
}

// Event listener untuk klik dua kali pada elemen harga
document.querySelectorAll(".editable").forEach((element) => {
  element.addEventListener("dblclick", function () {
    makeEditable(element);
  });
});

//
//
//
//
//

// Fungsi untuk mengurangi jumlah barang di keranjang
function kurangiJumlah(barangId, hargaSatuan, hargaButir) {
  const hargaAktual = hargaSatuan > 0 ? hargaSatuan : hargaButir;

  // Ambil data keranjang dari localStorage
  let keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];

  // Temukan barang di keranjang
  const existingItem = keranjang.find((item) => item.id === barangId);

  if (existingItem && existingItem.jumlahKeluar > 1) {
    // Kurangi jumlah barang
    existingItem.jumlahKeluar -= 1;

    // Tentukan harga yang digunakan
    existingItem.hargaAktual =
      existingItem.hargaSatuan > 0
        ? existingItem.hargaSatuan
        : existingItem.hargaButir;

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
      "Rp " + existingItem.jumlahKeluar * existingItem.hargaAktual;
  } else {
    // Jika jumlah barang sudah 1, bisa dihapus
    hapusBarangDariKeranjang(barangId);
  }

  // Update total harga semua barang di keranjang
  updateTotalHarga();
}

// Modifikasi fungsi lainnya juga dengan logika `hargaSatuan > 0 ? hargaSatuan : hargaButir`.

// Fungsi untuk mengupdate jumlah barang berdasarkan input
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

    // Perbarui jumlah barang di keranjang
    existingItem.jumlahKeluar = jumlah;

    // Simpan kembali ke localStorage
    localStorage.setItem("keranjang", JSON.stringify(keranjang));

    // Update tampilan total harga barang di keranjang
    const totalHargaElement = document.querySelector(
      `#item-${barangId} .totalHarga`
    );
    totalHargaElement.innerText =
      "Rp " + existingItem.jumlahKeluar * existingItem.hargaAktual;
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
    (total, item) =>
      total +
      (item.hargaSatuan > 0 ? item.hargaSatuan : item.hargaButir) *
        item.jumlahKeluar,
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
                       onblur="handleBlur('${item.id}', ${
      item.hargaSatuan
    }, this)">
                <button class="btn btn-sm btn-outline-secondary ms-2" onclick="tambahKeKeranjang('${
                  item.id
                }', '${item.namaBarang}', ${item.hargaSatuan}, ${
      item.hargaButir
    })">
                  <i class="fas fa-plus"></i>
                </button>
              </div>

              ${
                item.hargaAktual > 0
                  ? `<p class="editable">Rp ${item.hargaAktual}</p>`
                  : ""
              }
              <p class="totalHarga">Rp ${
                item.hargaSatuan * item.jumlahKeluar
              }</p>
              <button class="btn btn-sm btn-outline-danger ms-2" onclick="hapusBarangDariKeranjang('${
                item.id
              }')">
                <i class="fas fa-trash"></i>
              </button>
            </div>`;
    // <p>Rp ${item.hargaSatuan}</p>
    // <p>Rp ${item.hargaButir}</p>

    keranjangContainer.appendChild(keranjangItem);
  });

  // Update total harga setelah menampilkan barang
  updateTotalHarga();
}

// Panggil tampilkanKeranjang ketika halaman dimuat untuk menampilkan data keranjang yang sudah ada
document.addEventListener("DOMContentLoaded", tampilkanKeranjang);
