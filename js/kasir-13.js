function tambahKeKeranjang(
  barangId,
  namaBarang,
  hargaSatuan = 0,
  hargaButir = 0
) {
  const keranjangContainer = document.getElementById("keranjangContainer");
  let keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];
  const hargaAktual = hargaSatuan > 0 ? hargaSatuan : hargaButir;
  const existingItem = keranjang.find((item) => item.id === barangId);

  if (existingItem) {
    existingItem.jumlahKeluar += 1;
  } else {
    keranjang.push({
      id: barangId,
      namaBarang: namaBarang,
      hargaSatuan: hargaSatuan,
      hargaButir: hargaButir,
      hargaAktual: hargaAktual,
      jumlahKeluar: 1,
    });
  }

  localStorage.setItem("keranjang", JSON.stringify(keranjang));
  tampilkanKeranjang();
}

function kurangiJumlah(barangId) {
  let keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];
  const existingItem = keranjang.find((item) => item.id === barangId);

  if (existingItem) {
    existingItem.jumlahKeluar -= 1;
    if (existingItem.jumlahKeluar <= 0) {
      keranjang = keranjang.filter((item) => item.id !== barangId);
    }
  }

  localStorage.setItem("keranjang", JSON.stringify(keranjang));
  tampilkanKeranjang();
}

function updateJumlah(barangId, jumlahBaru) {
  let keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];
  const existingItem = keranjang.find((item) => item.id === barangId);

  if (existingItem) {
    existingItem.jumlahKeluar = Math.max(1, parseInt(jumlahBaru) || 1);
    localStorage.setItem("keranjang", JSON.stringify(keranjang));
    tampilkanKeranjang();
  }
}

function hapusBarangDariKeranjang(barangId) {
  let keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];
  keranjang = keranjang.filter((item) => item.id !== barangId);
  localStorage.setItem("keranjang", JSON.stringify(keranjang));
  tampilkanKeranjang();
}

function tampilkanKeranjang() {
  const keranjangContainer = document.getElementById("keranjangContainer");
  const totalHargaElement = document.getElementById("totalHargaSemuaBarang");
  let keranjang = JSON.parse(localStorage.getItem("keranjang")) || [];
  keranjangContainer.innerHTML = "";

  if (keranjang.length === 0) {
    keranjangContainer.innerHTML = "<p>Keranjang kosong.</p>";
    totalHargaElement.innerText = "Total: Rp 0";
    return;
  }

  let totalHarga = 0;

  keranjang.forEach((item) => {
    const itemDiv = document.createElement("div");
    itemDiv.classList.add("card", "w-100", "p-2", "m-1");
    itemDiv.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
          <h5>${item.namaBarang}</h5>
          <div class="d-flex align-items-center">
            <button class="btn btn-sm btn-outline-secondary" onclick="kurangiJumlah('${
              item.id
            }')">
              <i class="fas fa-minus"></i>
            </button>
            <input type="number" style="max-width: 80px;" class="form-control mx-2"
              value="${item.jumlahKeluar}" min="1"
              oninput="updateJumlah('${item.id}', this.value)">
            <button class="btn btn-sm btn-outline-secondary" onclick="tambahKeKeranjang('${
              item.id
            }', '${item.namaBarang}', ${item.hargaSatuan}, ${item.hargaButir})">
              <i class="fas fa-plus"></i>
            </button>
          </div>
          <p>Rp ${item.hargaAktual}</p>
          <p class="totalHarga">Rp ${item.hargaAktual * item.jumlahKeluar}</p>
          <button class="btn btn-sm btn-outline-danger" onclick="hapusBarangDariKeranjang('${
            item.id
          }')">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      `;
    keranjangContainer.appendChild(itemDiv);
    totalHarga += item.hargaAktual * item.jumlahKeluar;
  });

  totalHargaElement.innerText = "Total: Rp " + totalHarga;
}

// Tampilkan keranjang saat halaman dimuat
document.addEventListener("DOMContentLoaded", tampilkanKeranjang);
