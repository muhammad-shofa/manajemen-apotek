// Fungsi untuk menambah barang ke keranjang
function tambahKeKeranjang(barangId, namaBarang, hargaSatuan, hargaButir) {
  const keranjangContainer = document.getElementById("keranjangContainer");

  // Ambil data keranjang dari localStorage, jika ada
  let keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];

  // Cek apakah barang sudah ada di keranjang
  const existingItem = keranjang.find((item) => item.id === barangId);

  if (existingItem) {
    // Jika barang sudah ada, perbarui jumlah
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
      "Rp " +
      existingItem.jumlahKeluar *
        (existingItem.pilihanHarga === "satuan" ? hargaSatuan : hargaButir);
  } else {
    // Jika barang belum ada di keranjang, buat item baru
    const keranjangItem = {
      id: barangId,
      namaBarang: namaBarang,
      hargaSatuan: hargaSatuan,
      hargaButir: hargaButir,
      jumlahKeluar: 1,
      pilihanHarga: "satuan", // Default ke harga satuan
    };

    keranjang.push(keranjangItem);

    // Simpan ke localStorage
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
            <button class="btn btn-sm btn-outline-secondary me-2" onclick="kurangiJumlah('${barangId}', ${hargaSatuan}, ${hargaButir})">
              <i class="fas fa-minus"></i>
            </button>
            <input type="number" style="max-width: 80px;" class="jumlahKeluar form-control mx-2" value="1" min="1"
                   oninput="updateJumlah('${barangId}', ${hargaSatuan}, ${hargaButir}, this.value)" 
                   onblur="handleBlur('${barangId}', ${hargaSatuan}, ${hargaButir}, this)">
            <button class="btn btn-sm btn-outline-secondary ms-2" onclick="tambahKeKeranjang('${barangId}', '${namaBarang}', ${hargaSatuan}, ${hargaButir})">
              <i class="fas fa-plus"></i>
            </button>
          </div>
          <select class="form-select w-25" onchange="updatePilihanHarga('${barangId}', this.value)">
            <option value="satuan" selected>Harga Satuan: Rp ${hargaSatuan}</option>
            <option value="butir">Harga Butir: Rp ${hargaButir}</option>
          </select>
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

// Fungsi untuk mengupdate pilihan harga (satuan/butir)
function updatePilihanHarga(barangId, pilihan) {
  let keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];
  const existingItem = keranjang.find((item) => item.id === barangId);

  if (existingItem) {
    existingItem.pilihanHarga = pilihan;

    // Simpan kembali ke localStorage
    localStorage.setItem("keranjang", JSON.stringify(keranjang));

    // Update total harga barang tersebut
    const totalHargaElement = document.querySelector(
      `#item-${barangId} .totalHarga`
    );
    const harga =
      pilihan === "satuan" ? existingItem.hargaSatuan : existingItem.hargaButir;
    totalHargaElement.innerText = "Rp " + harga * existingItem.jumlahKeluar;
  }

  // Update total harga semua barang di keranjang
  updateTotalHarga();
}

// Modifikasi fungsi updateJumlah untuk menerima dua harga
function updateJumlah(barangId, hargaSatuan, hargaButir, jumlahBaru) {
  let keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];
  const existingItem = keranjang.find((item) => item.id === barangId);

  if (existingItem) {
    let jumlah = parseInt(jumlahBaru);

    if (isNaN(jumlah) || jumlah <= 0) {
      jumlah = 1;
    }

    existingItem.jumlahKeluar = jumlah;

    // Simpan kembali ke localStorage
    localStorage.setItem("keranjang", JSON.stringify(keranjang));

    // Update tampilan total harga
    const totalHargaElement = document.querySelector(
      `#item-${barangId} .totalHarga`
    );
    const harga =
      existingItem.pilihanHarga === "satuan" ? hargaSatuan : hargaButir;
    totalHargaElement.innerText = "Rp " + harga * jumlah;
  }

  // Update total harga semua barang di keranjang
  updateTotalHarga();
}
