function tambahKeKeranjang(barangId, namaBarang, hargaSatuan, hargaButir) {
  const keranjangContainer = document.getElementById("keranjangContainer");

  // Ambil data keranjang yang sudah disimpan di localStorage, jika ada
  let keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];

  const existingItem = keranjang.find((item) => item.id === barangId);

  if (existingItem) {
    // Jika ada, perbarui jumlah dan total harga
    existingItem.jumlahKeluar += 1;

    // Perbarui data keranjang di localStorage
    localStorage.setItem("keranjang", JSON.stringify(keranjang));

    // Update tampilan jumlah dan total harga
    const jumlahElement = document.querySelector(
      `#item-${barangId} .jumlahKeluar`
    );
    const totalHargaElement = document.querySelector(
      `#item-${barangId} .totalHarga`
    );
    // alert(existingItem);

    jumlahElement.value = existingItem.jumlahKeluar; // Update jumlah tampilan
    totalHargaElement.innerText =
      "Rp " + existingItem.jumlahKeluar * hargaSatuan; // Update total harga tampilan
  } else {
    // Jika belum ada, buat item baru di keranjang
    const keranjangItem = {
      id: barangId,
      namaBarang: namaBarang,
      hargaSatuan: hargaSatuan,
      hargaButir: hargaButir,
      jumlahKeluar: 1,
    };

    keranjang.push(keranjangItem);

    // Perbarui data keranjang di localStorage
    localStorage.setItem("keranjang", JSON.stringify(keranjang));

    // Buat elemen baru untuk menampilkan barang di keranjang
    const keranjangDiv = document.createElement("div");
    keranjangDiv.classList.add("card", "w-100", "d-flex", "p-2", "m-1");
    keranjangDiv.id = `item-${barangId}`;

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

function kurangiJumlah(barangId, hargaSatuan) {
  const keranjangContainer = document.getElementById("keranjangContainer");
  const existingItem = Array.from(keranjangContainer.children).find(
    (item) => item.getAttribute("data-id") === barangId
  );

  if (existingItem) {
    const jumlahElement = existingItem.querySelector(".jumlahKeluar");
    const totalHargaElement = existingItem.querySelector(".totalHarga");
    let jumlahKeluar =
      parseInt(existingItem.getAttribute("data-jumlah-keluar")) || 0;

    jumlahKeluar -= 1;

    if (jumlahKeluar <= 0) {
      // Hapus item jika jumlah 0
      keranjangContainer.removeChild(existingItem);
    } else {
      existingItem.setAttribute("data-jumlah-keluar", jumlahKeluar); // Update atribut data-jumlah-keluar
      jumlahElement.value = jumlahKeluar; // Update tampilan jumlah
      const totalHarga = jumlahKeluar * hargaSatuan;
      totalHargaElement.innerText = "Rp " + totalHarga;
    }
  }

  // Update total harga semua barang di keranjang
  updateTotalHarga();
}

function updateJumlah(barangId, hargaSatuan, jumlahBaru) {
  const keranjangContainer = document.getElementById("keranjangContainer");
  const existingItem = Array.from(keranjangContainer.children).find(
    (item) => item.getAttribute("data-id") === barangId
  );

  if (existingItem) {
    const totalHargaElement = existingItem.querySelector(".totalHarga");

    if (jumlahBaru === "") {
      // Jika input kosong, tunggu pengguna mengetikkan angka
      return;
    }

    let jumlah = parseInt(jumlahBaru);

    if (isNaN(jumlah) || jumlah <= 0) {
      jumlah = 1; // Default ke 1 jika input tidak valid
    }

    existingItem.setAttribute("data-jumlah-keluar", jumlah); // Update atribut data-jumlah-keluar
    const totalHarga = jumlah * hargaSatuan;
    totalHargaElement.innerText = "Rp " + totalHarga;
  }

  // Update total harga semua barang di keranjang
  updateTotalHarga();
}

function handleBlur(barangId, hargaSatuan, inputElement) {
  if (inputElement.value === "" || parseInt(inputElement.value) <= 0) {
    // Jika kosong atau tidak valid, set ke 1
    inputElement.value = 1;
    updateJumlah(barangId, hargaSatuan, inputElement.value);
  }
}

// Fungsi untuk menghitung total harga semua barang di keranjang
// function updateTotalHarga() {
//   const keranjangContainer = document.getElementById("keranjangContainer");
//   const totalHargaSemuaBarangElement = document.getElementById(
//     "totalHargaSemuaBarang"
//   ); // Elemen untuk menampilkan total harga semua barang
//   let totalHarga = 0;

//   // Loop melalui setiap item di keranjang dan tambahkan harga totalnya
//   Array.from(keranjangContainer.children).forEach((item) => {
//     const totalHargaItem = parseInt(
//       item.querySelector(".totalHarga").innerText.replace("Rp ", "")
//     );
//     totalHarga += totalHargaItem;
//   });

//   // Tampilkan total harga semua barang
//   totalHargaSemuaBarangElement.innerText = "Rp " + totalHarga;
// }

function updateTotalHarga() {
  const keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];
  const totalHargaElement = document.getElementById("totalHargaKeranjang");

  let totalHarga = keranjang.reduce(
    (total, item) => total + item.hargaSatuan * item.jumlahKeluar,
    0
  );
  totalHargaElement.innerText = "Total: Rp " + totalHarga;
}

// Hapus barang dari keranjang
function hapusBarangDariKeranjang(barangId) {
  const keranjangContainer = document.getElementById("keranjangContainer");
  const existingItem = Array.from(keranjangContainer.children).find(
    (item) => item.getAttribute("data-id") === barangId
  );

  if (existingItem) {
    keranjangContainer.removeChild(existingItem);
  }

  // Update total harga semua barang di keranjang
  updateTotalHarga();
}

// Fungsi untuk menyimpan data keranjang ke localStorage
function simpanKeranjangKeLocalStorage() {
  const keranjangContainer = document.getElementById("keranjangContainer");
  const items = Array.from(keranjangContainer.children).map((item) => {
    const barangId = item.getAttribute("data-id");
    const jumlahKeluar = parseInt(item.getAttribute("data-jumlah-keluar")) || 0;
    const hargaSatuan = parseInt(
      item.querySelector(".totalHarga").innerText.replace("Rp ", "") /
        jumlahKeluar
    );
    return {
      barangId,
      jumlahKeluar,
      hargaSatuan,
    };
  });
  localStorage.setItem("keranjang", JSON.stringify(items)); // Simpan ke localStorage
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

  // Setelah menampilkan keranjang, update total harga
  updateTotalHarga();
}

// Fungsi untuk menghitung dan menampilkan total harga keranjang
// function updateTotalHarga() {
//   const keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];
//   const totalHargaElement = document.getElementById("totalHargaKeranjang");

//   let totalHarga = keranjang.reduce(
//     (total, item) => total + item.hargaSatuan * item.jumlahKeluar,
//     0
//   );
//   totalHargaElement.innerText = "Total: Rp " + totalHarga;
// }

// Panggil tampilkanKeranjang saat halaman dimuat
window.onload = tampilkanKeranjang;
