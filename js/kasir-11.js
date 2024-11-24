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
                ? `<p class="hargaSatuan" ondblclick="editHarga('${barangId}', 'hargaSatuan', ${hargaSatuan})">Rp ${hargaSatuan}</p>`
                : ""
            }
            ${
              hargaButir > 0
                ? `<p class="hargaButir" ondblclick="editHarga('${barangId}', 'hargaButir', ${hargaButir})">Rp ${hargaButir}</p>`
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

// Fungsi untuk mengedit harga saat diklik 2 kali (ondblclick)
function editHarga(barangId, jenisHarga, hargaLama) {
  const hargaElement = document.querySelector(
    `#item-${barangId} .${jenisHarga}`
  );
  const input = document.createElement("input");
  input.type = "number";
  input.value = hargaLama;
  input.min = 0;
  input.classList.add("form-control");
  hargaElement.innerHTML = ""; // Kosongkan elemen harga
  hargaElement.appendChild(input);
  input.focus();

  // Ketika enter ditekan, update harga dan hitung ulang total harga
  input.addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
      const newHarga = parseFloat(input.value);
      if (!isNaN(newHarga) && newHarga > 0) {
        // Update harga sesuai dengan jenis (hargaSatuan atau hargaButir)
        updateHargaBarang(barangId, jenisHarga, newHarga);
        input.remove(); // Hapus input setelah selesai
      } else {
        alert("Harga tidak valid!");
        input.remove();
      }
    }
  });
}

// Fungsi untuk memperbarui harga barang dan total harga
function updateHargaBarang(barangId, jenisHarga, hargaBaru) {
  // Ambil data keranjang dari localStorage
  let keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];

  // Temukan barang yang akan diupdate
  const existingItem = keranjang.find((item) => item.id === barangId);

  if (existingItem) {
    // Update harga sesuai jenis harga (hargaSatuan atau hargaButir)
    if (jenisHarga === "hargaSatuan") {
      existingItem.hargaSatuan = hargaBaru;
    } else if (jenisHarga === "hargaButir") {
      existingItem.hargaButir = hargaBaru;
    }

    // Tentukan harga aktual yang digunakan
    existingItem.hargaAktual =
      existingItem.hargaSatuan > 0
        ? existingItem.hargaSatuan
        : existingItem.hargaButir;

    // Simpan kembali ke localStorage
    localStorage.setItem("keranjang", JSON.stringify(keranjang));

    // Update tampilan harga dan total harga di UI
    const totalHargaElement = document.querySelector(
      `#item-${barangId} .totalHarga`
    );
    totalHargaElement.innerText =
      "Rp " + existingItem.hargaAktual * existingItem.jumlahKeluar;
  }

  // Update total harga semua barang di keranjang
  updateTotalHarga();
}

// Fungsi untuk mengurangi jumlah barang di keranjang
function kurangiJumlah(barangId, hargaAktual) {
  // Ambil data keranjang dari localStorage
  let keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];

  // Temukan barang di keranjang
  const existingItem = keranjang.find((item) => item.id === barangId);

  if (existingItem && existingItem.jumlahKeluar > 1) {
    // Kurangi jumlah barang
    existingItem.jumlahKeluar -= 1;
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

// Fungsi untuk menghitung dan menampilkan total harga keranjang
function updateTotalHarga() {
  // Ambil data keranjang dari localStorage
  const keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];
  const totalHargaElement = document.getElementById("totalHargaSemuaBarang");

  let totalHarga = keranjang.reduce(
    (total, item) => total + item.hargaAktual * item.jumlahKeluar,
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
