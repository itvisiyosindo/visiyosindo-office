<aside id="sidebar-left" class="sidebar-left">
	<input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
	<div class="nano">
		<div class="nano-content">
			<nav id="menu" class="nav-main" role="navigation">
				<ul class="nav nav-main">
					<li>
						<span>
							<select class="form-control" style="width:90%; margin:5%" id="switch_menu">
								<option value="<?= base_url('dashboard') ?>">Home</option>
								<option value="<?= base_url('dashboard_dokumen') ?>">Dokumen Perusahaan</option>
								<option value="<?= base_url('dashboard_kepegawaian') ?>" >Kepegawaian</option>
								<option value="<?= base_url('dashboard_helpdesk') ?>">Helpdesk</option>
								<option value="<?= base_url('dashboard_inventory') ?>" selected>Inventory</option>
							</select>
						</span>
					</li>
					<?php if (isAdminInventory() || isStafAdmin()) { ?>

						<li class="nav-parent <?= in_array($page_name, ['v_virtual_account', 'v_bank','v_syarat_pembayaran', 'v_barang', 'v_kode_barang', 'v_ekspedisi', 'v_customer', 'v_gudang', 'v_kategori_barang', 'v_pemasok_utama', 'v_tarif_pajak', 'v_satuan_barang', 'v_cabang']) ? 'nav-active nav-expanded' : ''; ?>">
							<a class="nav-link" href="#">
								<i class="fas fa-database " aria-hidden="true"></i>
								<span>Master Data</span>
							</a>
							<ul class="nav nav-children">
								<li class="<?= $page_name == 'v_gudang' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('gudang') ?>">
										Gudang
									</a>
								</li>
								<li class="<?= $page_name == 'v_barang' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('barang') ?>">
										Barang
									</a>
								</li>
								<!-- <li class="<?= $page_name == 'v_kode_barang' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('kode_barang') ?>">
										Kode Barang / Barcode
									</a>
								</li> -->
								<li class="<?= $page_name == 'v_cabang' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('cabang') ?>">
										Cabang
									</a>
								</li>
								<li class="<?= $page_name == 'v_kategori_barang' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('kategori_barang') ?>">
										Kategori Barang
									</a>
								</li>

								<li class="<?= $page_name == 'v_pemasok_utama' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('pemasok_utama') ?>">
										Pemasok Utama
									</a>
								</li>
								<li class="<?= $page_name == 'v_tarif_pajak' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('tarif_pajak') ?>">
										Tarif Pajak
									</a>
								</li>
								<li class="<?= $page_name == 'v_satuan_barang' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('satuan_barang') ?>">
										Satuan Barang
									</a>
								</li>
								<!-- <li class="<?= $page_name == 'v_barang' ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('barang') ?>">
									Barang & Jasa
								</a>
							</li> -->
								<li class="<?= $page_name == 'v_customer' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('customer') ?>">
										Customer
									</a>
								</li>
								<li class="<?= $page_name == 'v_ekspedisi' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('ekspedisi') ?>">
										Ekspedisi
									</a>
								</li>
								<li class="<?= $page_name == 'v_syarat_pembayaran' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('syarat_pembayaran') ?>">
										Syarat Pembayaran
									</a>
								</li>
								<li class="<?= $page_name == 'v_bank' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('bank') ?>">
										Bank
									</a>
								</li>
								<li class="<?= $page_name == 'v_virtual_account' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('virtual_account') ?>">
										Virtual Account
									</a>
								</li>
							</ul>
						</li>
					<?php } ?>
					
						<li class="nav-parent <?= in_array($page_name, ['v_stock_gudang', 'v_penerimaan_barang', 'v_pengeluaran_barang', 'v_penerimaan_barang_form', 'v_pengeluaran_barang_form', 'v_pengeluaran_barang_form_invoice']) ? 'nav-active nav-expanded' : ''; ?>">
							<a class="nav-link" href="#">
								<i class="fas fa-boxes " aria-hidden="true"></i>
								<span>Persediaan</span>
							</a>
							<ul class="nav nav-children">
								<li class="<?= $page_name == 'v_stock_gudang' ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('stock_gudang') ?>">
										Stock Gudang
									</a>
								</li>
								<?php if (isAdminInventory() || isStafAdmin() || isMarketing()) { ?>
								<li class="<?= in_array($page_name, ['v_penerimaan_barang', 'v_penerimaan_barang_form']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('penerimaan_barang') ?>">
										Penerimaan Barang
									</a>
								</li>
								<li class="<?= in_array($page_name, ['v_pengeluaran_barang', 'v_pengeluaran_barang_form', 'v_pengeluaran_barang_form_invoice']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('pengeluaran_barang') ?>">
										Pengeluaran Barang
									</a>
								</li>
								<?php } ?>
							</ul>
						</li>
					
					<?php if (isAdminInventory() || isStafAdmin()) { ?>
						<li class="nav-parent <?= in_array($page_name, ['v_penerimaan_stok', 'v_pengiriman_stok', 'v_pengiriman_stok_form', 'v_penerimaan_stok_form']) ? 'nav-active nav-expanded' : ''; ?>">
							<a class="nav-link" href="#">
								<i class="fas fa-truck-moving" aria-hidden="true"></i>
								<span>Pemindahan Stock</span>
							</a>
							<ul class="nav nav-children">
								<li class="<?= in_array($page_name, ['v_pengiriman_stok', 'v_pengiriman_stok_form']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('pengiriman_stok') ?>">
										Pengiriman Stock
									</a>
								</li>
								<li class="<?= in_array($page_name, ['v_penerimaan_stok', 'v_penerimaan_stok_form']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('penerimaan_stok') ?>">
										Penerimaan Stock
									</a>
								</li>
							</ul>
						</li>
					<?php } ?>
										<?php if (isAdminInventory() || isStafAdmin()) { ?>
						<li
							class="nav-parent <?= in_array($page_name, ['v_permintaan_penawaran']) ? 'nav-active nav-expanded' : ''; ?>">
							<a class="nav-link" href="#">
								<i class="fas fa-calculator" aria-hidden="true"></i>
								<span>Penjualan</span> &nbsp;<label class=" col-form-label"><span class="text-danger">Dalam Proses</span></label>
							</a>
							<ul class="nav nav-children">
								<li class="<?= in_array($page_name, ['v_permintaan_penawaran', 'v_permintaan_penawaran']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('permintaan_penawaran') ?>">
										Permintaan Penawaran
									</a>
								</li>

							</ul>
						</li>
					<?php } ?>
					<?php if (isAdminInventory() || isStafAdmin() || isMarketing()) { ?>
						<li class="<?= in_array($page_name, ['v_invoice','v_invoice_form', 'v_invoice_form_pengeluaran_barang']) ? 'nav-active' : '' ?>">
							<a class="nav-link" href="<?= base_url('invoice') ?>">
								<i class="fas fa-file-invoice-dollar"></i>
								<span>Invoice</span>
							</a>
						</li>
					<?php } ?>
					<li class="nav-parent <?= in_array($page_name, ['v_brosur', 'v_price_list', 'v_pop_penjualan_produk', 'v_kalkulasi_cicilan', 'v_detail_package_mesin', 'v_bhn_presentasi', 'inv_lain2/v_video_tutorial']) ? 'nav-active nav-expanded' : ''; ?>">
						<a class="nav-link" href="#">
							<i class="fas fa-align-left"></i>
							<span>Lain - lain</span>
						</a>
						<ul class="nav nav-children">
							<li class="<?= $page_name == 'v_brosur' ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('brosur') ?>">
									Brosur
								</a>
							</li>
							<li class="<?= $page_name == 'v_detail_package_mesin' ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('detail_package_mesin') ?>">
									Detail Package Mesin
								</a>
							</li>
							<li class="<?= $page_name == 'v_bhn_presentasi' ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('bahan_presentasi') ?>">
									Bahan Presentasi
								</a>
							</li>
							<li class="<?= $page_name == 'inv_lain2/v_video_tutorial' ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('video_tutorial') ?>">
									Video Tutorial
								</a>
							</li>
							<li class="<?= $page_name == 'v_price_list' ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('price_list') ?>">
									Price List
								</a>
							</li>
							<li class="<?= $page_name == 'v_pop_penjualan_produk' ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('pop_penjualan_produk') ?>">
									Populasi Penjualan Produk
								</a>
							</li>
							<li class="<?= $page_name == 'v_kalkulasi_cicilan' ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('kalkulasi_cicilan') ?>">
									Kalkulasi Cicilan
								</a>
							</li>
						</ul>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</aside>

<script>
    document.getElementById("switch_menu").onchange = function() {
        if (this.value!=="") {
            window.location.href = this.value;
        }        
    };
</script>