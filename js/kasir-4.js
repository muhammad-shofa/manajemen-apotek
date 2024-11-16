function tambahKeKeranjang(barangId, namaBarang, hargaSatuan) {
    const keranjangContainer = document.getElementById("keranjangContainer");
  
    const existingItem = Array.from(keranjangContainer.children).find(
      (item) => item.getAttribute("data-id") === barangId
    );
  
    if (existingItem) {
      // Jika ada, perbarui jumlah dan total harga
      const jumlahElement = existingItem.querySelector(".jumlahKeluar");
      const totalHargaElement = existingItem.querySelector(".totalHarga");
      const jumlahKeluar = parseInt(jumlahElement.value) || 0;
  
      let jumlah = jumlahKeluar + 1;
      jumlahElement.value = jumlah; // Update tampilan jumlah
  
      const totalHarga = jumlah * hargaSatuan;
      totalHargaElement.innerText = "Rp " + totalHarga;
    } else {
      // Jika belum ada, buat item baru di keranjang
      const keranjangItem = document.createElement("div");
      keranjangItem.classList.add("card", "w-100", "d-flex", "p-2", "m-1");
      keranjangItem.setAttribute("data-id", barangId);
  
      keranjangItem.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="text-dark">${namaBarang}</h5>
            <div class="d-flex align-items-center">
                <button class="btn btn-sm btn-outline-secondary me-2" onclick="kurangiJumlah('${barangId}', ${hargaSatuan})">
                    <i class="fas fa-minus"></i>
                </button>
                <input type="number" style="max-width: 80px;" class="jumlahKeluar form-control mx-2" value="1" min="1" oninput="updateJumlah('${barangId}', ${hargaSatuan}, this.value)">
                <button class="btn btn-sm btn-outline-secondary ms-2" onclick="tambahKeKeranjang('${barangId}', '${namaBarang}', ${hargaSatuan})">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <p>Rp ${hargaSatuan}</p>
            <p class="totalHarga">Rp ${hargaSatuan}</p>
        </div>`;
  
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
      const jumlahKeluar = parseInt(jumlahElement.value) || 0;
  
      let jumlah = jumlahKeluar - 1;
  
      if (jumlah <= 0) {
        // Hapus item jika jumlah 0
        keranjangContainer.removeChild(existingItem);
      } else {
        jumlahElement.value = jumlah; // Update tampilan jumlah
        const totalHarga = jumlah * hargaSatuan;
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
        existingItem.querySelector(".jumlahKeluar").value = jumlah;
      }
  
      const totalHarga = jumlah * hargaSatuan;
      totalHargaElement.innerText = "Rp " + totalHarga;
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
  