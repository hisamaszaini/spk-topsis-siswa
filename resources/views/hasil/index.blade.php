@extends('layouts.app')

@section('title', 'Hasil Rekomendasi Lomba')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-award mr-1"></i> Hasil Rekomendasi Lomba</h1>
    <div>
        <a href="#" onclick="window.print()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Cetak / PDF
        </a>
        <button onclick="exportActiveTable()" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm ml-2">
            <i class="fas fa-file-excel fa-sm text-white-50"></i> Export Excel
        </button>
    </div>
</div>

@if(isset($error))
<div class="alert alert-info shadow border-left-info">
    <i class="fas fa-info-circle mr-2"></i> {{ $error }}
</div>
@else
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-primary">
        <h6 class="m-0 font-weight-bold text-white">Ranking Hasil Rekomendasi (Metode TOPSIS)</h6>
    </div>
    <div class="card-body">
        <!-- Nav Tabs -->
        <ul class="nav nav-tabs mb-4" id="lombaTabs" role="tablist">
            @foreach($listLomba as $key => $lomba)
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $loop->first ? 'active font-weight-bold text-primary' : 'text-secondary' }}" 
                   id="tab-{{ $key }}" 
                   data-toggle="tab" 
                   href="#content-{{ $key }}" 
                   role="tab" 
                   aria-controls="content-{{ $key }}" 
                   aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ $lomba['nama'] }}
                </a>
            </li>
            @endforeach
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="lombaTabsContent">
            @foreach($results as $key => $dataLomba)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="content-{{ $key }}" role="tabpanel" aria-labelledby="tab-{{ $key }}">
                <div class="table-responsive">
                    <table class="table table-bordered text-dark table-striped" id="dataTable-{{ $key }}" width="100%" cellspacing="0">
                        <thead class="bg-gray-100">
                            <tr>
                                <th width="10%" class="text-center">Ranking</th>
                                <th>Nama Siswa</th>
                                <th class="text-right" width="25%">Nilai Kedekatan (Ci)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataLomba['preference'] as $index => $item)
                            <tr class="{{ $index == 0 ? 'bg-light font-weight-bold text-primary' : '' }}">
                                <td class="text-center">
                                    @if($index == 0)
                                    <span class="badge badge-warning rounded-pill p-2"><i class="fas fa-crown"></i> 1</span>
                                    @elseif($index == 1)
                                    <span class="badge badge-secondary rounded-pill p-2">2</span>
                                    @elseif($index == 2)
                                    <span class="badge badge-danger rounded-pill p-2">3</span>
                                    @else
                                    {{ $index + 1 }}
                                    @endif
                                </td>
                                <td class="font-weight-bold">{{ $item['nama_siswa'] }}</td>
                                <td class="text-right font-weight-bold text-primary">{{ number_format($item['score'], 4) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #content-wrapper,
        #content-wrapper * {
            visibility: visible;
        }

        #content-wrapper {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .no-print {
            display: none !important;
        }

        /* Hide Sidebar, Topbar, Buttons */
        #accordionSidebar,
        #page-topbar,
        .btn,
        .nav-tabs {
            display: none !important;
        }
    }
</style>

<script>
    function exportActiveTable() {
        var activeTabPane = document.querySelector('.tab-pane.active');
        if (activeTabPane) {
            var table = activeTabPane.querySelector('table');
            if (table) {
                var activeLombaName = document.querySelector('.nav-link.active').innerText.trim();
                var cleanName = activeLombaName.toLowerCase().replace(/[^a-z0-9]+/g, '-');
                exportTableToExcel(table.id, 'hasil-topsis-' + cleanName);
            }
        }
    }

    function exportTableToExcel(tableID, filename) {
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

        filename = filename ? filename + '.xls' : 'excel_data.xls';
        downloadLink = document.createElement("a");
        document.body.appendChild(downloadLink);

        if (navigator.msSaveOrOpenBlob) {
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
            downloadLink.download = filename;
            downloadLink.click();
        }
    }

    // Toggle active font styles on tab click
    document.addEventListener('DOMContentLoaded', function () {
        var tabElements = document.querySelectorAll('a[data-toggle="tab"]');
        tabElements.forEach(function (tab) {
            tab.addEventListener('shown.bs.tab', function (e) {
                tabElements.forEach(function (t) {
                    t.classList.remove('font-weight-bold', 'text-primary');
                    t.classList.add('text-secondary');
                });
                e.target.classList.add('font-weight-bold', 'text-primary');
                e.target.classList.remove('text-secondary');
            });
        });
    });
</script>
@endsection