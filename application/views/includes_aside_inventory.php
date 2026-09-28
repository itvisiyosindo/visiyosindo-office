<aside id="sidebar-left" class="sidebar-left">
  <input type="hidden" name="token" value="<?= $this->security->get_csrf_hash() ?>">
  <!-- Mobile Sidebar Header with Close Button -->
  <div class="sidebar-header-mobile d-md-none">
    <div class="sidebar-mobile-title">Office Menu</div>
    <button class="sidebar-mobile-close" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
      <i class="fas fa-times"></i>
    </button>
  </div>
  <div class="nano">
    <div class="nano-content">
      <nav id="menu" class="nav-main" role="navigation">
        <ul class="nav nav-main">
          <li>
            <span>
              <select class="form-control" style="width:90%; margin:5%" id="switch_menu">
                <option value="<?= base_url('dashboard') ?>">Home</option>
                <option value="<?= base_url('dashboard_dokumen') ?>">Dokumen Perusahaan</option>
                <option value="<?= base_url('dashboard_kepegawaian') ?>">Kepegawaian</option>
                <option value="<?= base_url('dashboard_helpdesk') ?>">Helpdesk</option>
                <option value="<?= base_url('dashboard_inventory') ?>" selected>Inventory</option>
                <?php if (isAdmin() || isHrd() || isTeamMarketing() || isCRO() || isGa() || sessPenggunaId() == '75' || sessPenggunaId() == '755' || sessPenggunaId() == '64') { ?>
                  <option value="<?= base_url('dashboard_marketing') ?>">Marketing</option>
                <?php } ?>
                <?php if (isAdmin() || isHrd() || isCRO() || isGa() || sessPenggunaId() == '54' || sessPenggunaId() == '84' || sessPenggunaId() == '75' || sessPenggunaId() == '15' || sessPenggunaId() == '736' || sessPenggunaId() == '754' || sessPenggunaId() == '751' || sessPenggunaId() == '25' || sessPenggunaId() == '757' || sessPenggunaId() == '765' || sessPenggunaId() == '755' || sessPenggunaId() == '770') { ?>
                  <option value="<?= base_url('dashboard_visilab') ?>">Visilab</option>
                <?php } ?>
              
								<?php if (isAccountingUser()) { ?>
									<option value="<?= base_url('acc_pemasok') ?>" <?= $switch == 'accounting' ? 'selected' : '' ?>>Accounting & Tax</option>
								<?php } ?>
							</select>
            </span>
          </li>
          <?php if (isAdminInventory() || isStafAdmin() || sessPenggunaId() == '58' || sessPenggunaId() == '763' || sessPenggunaId() == '769') { ?>

            <li
              class="nav-parent <?= in_array($page_name, ['v_virtual_account', 'v_bank', 'v_syarat_pembayaran', 'v_barang', 'v_kode_barang', 'v_customer', 'v_gudang', 'v_kategori_barang', 'v_pemasok_utama', 'v_tarif_pajak', 'v_satuan_barang', 'v_cabang', 'aset/v_aset', 'aset/v_detail_aset']) ? 'nav-active nav-expanded' : ''; ?>">
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

                <li class="<?= $page_name == 'v_customer' ? 'nav-active' : '' ?>">
                  <a class="nav-link" href="<?= base_url('customer') ?>">
                    Customer
                  </a>
                </li>

                <li class="<?= in_array($page_name, ['aset/v_aset', 'aset/v_detail_aset']) ? 'nav-active' : '' ?>">
                  <a class="nav-link" href="<?= base_url('aset/show/list') ?>">
                    Aset Perusahaan
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

          <li class="nav-parent <?= in_array($page_name, [
                                  'v_stock_gudang',
                                  'v_stock_gudang_default',
                                  'v_stock_gudang_pusat',
                                  'v_stock_gudang_jkt',
                                  'v_stock_gudang_yogya',
                                  'v_stock_gudang_jual',
                                  'v_stock_gudang_demo',
                                  'v_stock_gudang_rusak',
                                  'v_stock_gudang_marketing',
                                  'v_stock_gudang_sparepart',
                                  'v_stock_gudang_customer',
                                  'v_penerimaan_barang',
                                  'v_pengeluaran_barang',
                                  'v_penerimaan_barang_form',
                                  'v_pengeluaran_barang_form',
                                  'v_pengeluaran_barang_form_invoice',
                                  'v_permintaan_penawaran',
                                  'purchase_order/v_purchase_order',
                                  'purchase_order/v_aju_po',
                                  'purchase_order/v_detail_po',
                                  'serah_terima_barang/v_stb',
                                  'serah_terima_barang/v_aju_stb',
                                  'serah_terima_barang/v_detail_stb',
                                  'stock_opname/v_stock_opname',
                                  'stock_opname/v_detail_so',
                                  'history_barang/v_history_barang',
                                  'purchase_order/v_purchase_order1',
                                  'purchase_order/v_aju_po1',
                                  'purchase_order/v_detail_po1',
                                  'stock/v_stock',
                                  'forecast/v_forecast',
                                  'forecast/v_detail_forecast',
                                  'forecast/v_detail_forecast_isi'
                                ]) ? 'nav-active nav-expanded' : ''; ?>">
            <a class="nav-link" href="#">
              <i class="fas fa-boxes " aria-hidden="true"></i>
              <span>Persediaan</span>
            </a>
            <ul class="nav nav-children">
              <!--<li class="<?= in_array($page_name, ['v_stock_gudang']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('stock_gudang') ?>">
										Stock Gudang
									</a>
								</li>-->
              <li
                class="<?= in_array($page_name, ['v_stock_gudang_default', 'v_stock_gudang_pusat', 'v_stock_gudang_jkt', 'v_stock_gudang_yogya', 'v_stock_gudang_jual', 'v_stock_gudang_demo', 'v_stock_gudang_rusak', 'v_stock_gudang_marketing', 'v_stock_gudang_sparepart', 'v_stock_gudang_customer']) ? 'nav-active' : '' ?>">
                <a class="nav-link" href="<?= base_url('stock_gudang/show/default') ?>">
                  Stock Gudang
                  <!--<strong>Beta</strong>-->
                </a>
              </li>
              <li class="<?= in_array($page_name, ['stock/v_stock']) ? 'nav-active' : '' ?>">
                <a class="nav-link" href="<?= base_url('stock') ?>">
                  Stock Barang
                  <!--<strong>Beta</strong>-->
                </a>
              </li>
              <?php if (isAdminInventory() || isStafAdmin() || isMarketing() || isCRO() || sessPenggunaId() == '102' || sessPenggunaId() == '743' || sessPenggunaId() == '764') { ?>
                <li
                  class="<?= in_array($page_name, ['v_penerimaan_barang', 'v_penerimaan_barang_form']) ? 'nav-active' : '' ?>">
                  <a class="nav-link" href="<?= base_url('penerimaan_barang') ?>">
                    Penerimaan Barang
                  </a>
                </li>
                <li
                  class="<?= in_array($page_name, ['v_pengeluaran_barang', 'v_pengeluaran_barang_form', 'v_pengeluaran_barang_form_invoice']) ? 'nav-active' : '' ?>">
                  <a class="nav-link" href="<?= base_url('pengeluaran_barang') ?>">
                    Pengeluaran Barang
                  </a>
                </li>
              <?php } ?>
              <!--<li class="<?= in_array($page_name, ['purchase_order/v_purchase_order1', 'purchase_order/v_aju_po1', 'purchase_order/v_detail_po1']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('purchase_order/show/permintaan/purchase_order1') ?>">
									Pengajuan PO Supplier
									</a>
								</li>-->
              <li
                class="<?= in_array($page_name, ['purchase_order/v_purchase_order', 'purchase_order/v_aju_po', 'purchase_order/v_detail_po']) ? 'nav-active' : '' ?>">
                <a class="nav-link" href="<?= base_url('purchase_order/show/permintaan/purchase_order') ?>">
                  Pengajuan PO Supplier
                </a>
              </li>
              <li
                class="<?= in_array($page_name, ['forecast/v_forecast', 'forecast/v_detail_forecast', 'forecast/v_detail_forecast_isi']) ? 'nav-active' : '' ?>">
                <a class="nav-link" href="<?= base_url('forecast/show/list') ?>">
                  Pengajuan Forecast
                </a>
              </li>
              <li
                class="<?= in_array($page_name, ['serah_terima_barang/v_stb', 'serah_terima_barang/v_aju_stb', 'serah_terima_barang/v_detail_stb']) ? 'nav-active' : '' ?>">
                <a class="nav-link" href="<?= base_url('serah_terima_barang/show/permintaan/stb') ?>">
                  Serah Terima Barang
                </a>
              </li>
              <li
                class="<?= in_array($page_name, ['stock_opname/v_stock_opname', 'stock_opname/v_detail_so']) ? 'nav-active' : '' ?>">
                <a class="nav-link" href="<?= base_url('stock_opname') ?>">
                  Stock Opname
                </a>
              </li>
              <li class="<?= in_array($page_name, ['history_barang/v_history_barang']) ? 'nav-active' : '' ?>">
                <a class="nav-link" href="<?= base_url('history_barang') ?>">
                  History Barang &nbsp; <strong>Beta</strong>
                </a>
              </li>

              <!--
								<li class="<?= in_array($page_name, ['history_barang/v_history_barang_2']) ? 'nav-active' : '' ?>">
									<a class="nav-link" href="<?= base_url('history_barang/show_all') ?>">
									History Barang &nbsp; <strong>Beta</strong>
									</a>
								</li>-->


              <?php /*if(sessPenggunaId()=='1'){ ?>
              <li class="<?= in_array($page_name, ['history_barang/v_history_barang_2']) ? 'nav-active' : '' ?>">
                <a class="nav-link" href="<?= base_url('history_barang/coba') ?>">
                  History Barang &nbsp; <strong>COBA</strong>
                </a>
              </li>
              <?php } */ ?>


            </ul>
          </li>


          <?php if (isAdminInventory() || isStafAdmin() || sessPenggunaId() == '763' || sessPenggunaId() == '769') { ?>
            <li
              class="nav-parent <?= in_array($page_name, ['v_penerimaan_stok', 'v_pengiriman_stok', 'v_pengiriman_stok_form', 'v_penerimaan_stok_form']) ? 'nav-active nav-expanded' : ''; ?>">
              <a class="nav-link" href="#">
                <i class="fas fa-truck-moving" aria-hidden="true"></i>
                <span>Pemindahan Stock</span>
              </a>
              <ul class="nav nav-children">
                <li
                  class="<?= in_array($page_name, ['v_pengiriman_stok', 'v_pengiriman_stok_form']) ? 'nav-active' : '' ?>">
                  <a class="nav-link" href="<?= base_url('pengiriman_stok') ?>">
                    Pengiriman Stock
                  </a>
                </li>
                <li
                  class="<?= in_array($page_name, ['v_penerimaan_stok', 'v_penerimaan_stok_form']) ? 'nav-active' : '' ?>">
                  <a class="nav-link" href="<?= base_url('penerimaan_stok') ?>">
                    Penerimaan Stock
                  </a>
                </li>
              </ul>
            </li>
          <?php } ?>

          <li
            class="<?= in_array($page_name, ['inventory/v_detail_po_pending', 'inventory/v_aju_po_pending', 'inventory/v_po_pending']) ? 'nav-active' : '' ?>">
            <a class="nav-link" href="<?= base_url('inventory_new/show/list/po_pending') ?>">
              <i class="fas fa-digital-tachograph" aria-hidden="true"></i>
              <span>Po Pending</span>
            </a>
          </li>

          <li
            class="nav-parent <?= in_array($page_name, [
                                'ekspedisi/v_ekspedisi',
                                'ekspedisi/v_destinasi',
                                'ekspedisi/v_acuan_ongkir',
                                'ekspedisi/v_pricelist',
                                'ekspedisi/v_detail_pricelist',
                                'ekspedisi/v_nama_ekspedisi',
                                'ekspedisi/v_detail_nama_ekspedisi',
                                'ekspedisi/v_nama_barang',
                                'ekspedisi/v_detail_nama_barang'
                              ]) ? 'nav-active nav-expanded' : ''; ?>">
            <a class="nav-link" href="#">
              <i class="fas fa-truck" aria-hidden="true"></i>
              <span>Perbandingan Ekspedisi</span>
            </a>
            <ul class="nav nav-children">
              <li class="<?= $page_name == 'ekspedisi/v_ekspedisi' ? 'nav-active' : '' ?>">
                <a class="nav-link" href="<?= base_url('ekspedisi/show/list/ekspedisi') ?>">
                  Master Data Ekspedisi
                </a>
              </li>
              <li class="<?= in_array($page_name, ['ekspedisi/v_destinasi']) ? 'nav-active' : '' ?>">
                <a class="nav-link" href="<?= base_url('ekspedisi/show/list/destinasi') ?>">
                  Master Data Kab/Kota
                </a>
              </li>
              <li class="<?= in_array($page_name, ['ekspedisi/v_acuan_ongkir']) ? 'nav-active' : '' ?>">
                <a class="nav-link" href="<?= base_url('ekspedisi/show/list/acuan_ongkir') ?>">
                  Master Data Berat dan Acuan Ongkir Maksimal
                </a>
              </li>
              <li
                class="<?= in_array($page_name, ['ekspedisi/v_pricelist', 'ekspedisi/v_detail_pricelist']) ? 'nav-active' : '' ?>">
                <a class="nav-link" href="<?= base_url('ekspedisi/show/list/pricelist') ?>">
                  Pricelist Ekspedisi
                </a>
              </li>
              <li
                class="<?= in_array($page_name, ['ekspedisi/v_nama_ekspedisi', 'ekspedisi/v_detail_nama_ekspedisi']) ? 'nav-active' : '' ?>">
                <a class="nav-link" href="<?= base_url('ekspedisi/show/list/berdasarkan_ekspedisi') ?>">
                  Berdasarkan Nama Expedisi
                </a>
              </li>
              <li
                class="<?= in_array($page_name, ['ekspedisi/v_nama_barang', 'ekspedisi/v_detail_nama_barang']) ? 'nav-active' : '' ?>">
                <a class="nav-link" href="<?= base_url('ekspedisi/show/list/berdasarkan_barang') ?>">
                  Berdasarkan Nama Barang
                </a>
              </li>
            </ul>
          </li>


          <li
            class="nav-parent <?= in_array($page_name, ['v_ekspedisi', 'tracking/v_tracking', 'tracking/v_tracking_dokumen', 'tracking/v_tracking_dokumen_new', 'tracking/v_tracking_dokumen_manage', 'tracking/v_detail_tracking']) ? 'nav-active nav-expanded' : ''; ?>">
            <a class="nav-link" href="#">
              <i class="fas fa-shipping-fast" aria-hidden="true"></i>
              <span>Tracking Barang & Dokumen</span>
            </a>
            <ul class="nav nav-children">
              <li class="<?= $page_name == 'v_ekspedisi' ? 'nav-active' : '' ?>">
                <a class="nav-link" href="<?= base_url('ekspedisi') ?>">
                  Database Ekspedisi
                </a>
              </li>
              <li
                class="<?= $page_name == 'tracking/v_tracking' ? 'nav-active' : '' ?>">
                <a class="nav-link" href="<?= base_url('tracking') ?>">
                  Data Tracking Barang
                </a>
              </li>
              <li
                class="<?= in_array($page_name, ['tracking/v_tracking_dokumen', 'tracking/v_tracking_dokumen_new', 'tracking/v_tracking_dokumen_manage']) ? 'nav-active' : '' ?>">
                <a class="nav-link" href="<?= base_url('tracking/tracking_dokumen') ?>">
                  Data Tracking Dokumen
                </a>
              </li>
            </ul>
          </li>

          <li class="<?= in_array($page_name, ['pinjam/v_detail_pinjam', 'pinjam/v_aju_pinjam', 'pinjam/v_pinjam']) ? 'nav-active' : '' ?>">
            <a class="nav-link" href="<?= base_url('pinjam/show/list') ?>">
              <i class="fas fa-box-open" aria-hidden="true"></i>
              <span>Peminjaman Unit</span>
            </a>
          </li>


          <?php if (isAdminInventory() || isStafAdmin() || isMarketing() || sessPenggunaId() == 763 || sessPenggunaId() == 769) { ?>
            <li
              class="<?= in_array($page_name, ['v_invoice', 'v_invoice_form', 'v_invoice_form_pengeluaran_barang']) ? 'nav-active' : '' ?>">
              <a class="nav-link" href="<?= base_url('invoice') ?>">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Invoice</span>
              </a>
            </li>
          <?php } ?>
          <?php if (false) { ?>
            <li
              class="nav-parent <?= in_array($page_name, ['v_brosur', 'v_price_list', 'kalkulator/v_kalkulator', 'kalkulator/v_kalkulator_gov', 'v_pop_penjualan_produk', 'v_kalkulasi_cicilan', 'v_detail_package_mesin', 'inv_lain2/v_video_tutorial']) ? 'nav-active nav-expanded' : ''; ?>">
              <a class="nav-link" href="#">
                <i class="fas fa-box"></i>
                <span>Produk</span>
              </a>
              <ul class="nav nav-children">
                <!--<li class="<?= $page_name == 'v_brosur' ? 'nav-active' : '' ?>">
								<a class="nav-link" href="<?= base_url('brosur') ?>">
									Brosur
								</a>
							</li>-->
                <li class="<?= $page_name == 'v_detail_package_mesin' ? 'nav-active' : '' ?>">
                  <a class="nav-link" href="<?= base_url('detail_package_mesin') ?>">
                    Detail Package Mesin
                  </a>
                </li>
                <li class="<?= $page_name == 'v_price_list' ? 'nav-active' : '' ?>">
                  <a class="nav-link" href="<?= base_url('price_list') ?>">
                    Price List
                  </a>
                </li>
                <li class="<?= $page_name == 'kalkulator/v_kalkulator' ? 'nav-active' : '' ?>">
                  <a class="nav-link" href="<?= base_url('kalkulator') ?>">
                    Kalkulator Price List Swasta
                  </a>
                </li>
                <li class="<?= $page_name == 'kalkulator/v_kalkulator_gov' ? 'nav-active' : '' ?>">
                  <a class="nav-link" href="<?= base_url('kalkulator/gov') ?>">
                    Kalkulator Price List Government
                  </a>
                </li>
                <li class="<?= $page_name == 'v_pop_penjualan_produk' ? 'nav-active' : '' ?>">
                  <a class="nav-link" href="<?= base_url('pop_penjualan_produk') ?>">
                    Populasi Penjualan Produk
                  </a>
                </li>
              </ul>
            </li>
          <?php } ?>
          <li
            class="nav-parent <?= in_array($page_name, ['v_brosur', 'v_pop_penjualan_produk', 'v_kalkulasi_cicilan', 'v_detail_package_mesin', 'inv_lain2/v_video_tutorial']) ? 'nav-active nav-expanded' : ''; ?>">
            <a class="nav-link" href="#">
              <i class="fas fa-align-left"></i>
              <span>Lain - lain</span>
            </a>
            <ul class="nav nav-children">
              <li class="<?= $page_name == 'inv_lain2/v_video_tutorial' ? 'nav-active' : '' ?>">
                <a class="nav-link" href="<?= base_url('video_tutorial') ?>">
                  Video Tutorial
                </a>
              </li>
              <li class="<?= $page_name == 'v_kalkulasi_cicilan' ? 'nav-active' : '' ?>">
                <a class="nav-link" href="<?= base_url('kalkulasi_cicilan') ?>">
                  Kalkulasi Cicilan
                </a>
              </li>

            </ul>
          </li>
          <li>
            <a class="nav-link text-danger" href="<?= base_url('auth/logout') ?>">
              <i class="fas fa-sign-out-alt text-danger" aria-hidden="true"></i>
              <span>Logout</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</aside>

<script>
  document.getElementById("switch_menu").onchange = function() {
    if (this.value !== "") {
      window.location.href = this.value;
    }
  };
</script>