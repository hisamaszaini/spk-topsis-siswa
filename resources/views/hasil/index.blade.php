@extends('layouts.app')

@section('title', 'Hasil Akhir')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-trophy mr-1"></i> Data Hasil Akhir</h1>
    <div>
        <a href="#" onclick="window.print()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Cetak / PDF
        </a>
        <button onclick="exportTableToExcel('dataTable')" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm ml-2">
            <i class="fas fa-file-excel fa-sm text-white-50"></i> Export Excel
        </button>
    </div>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger">Ranking SAW</h6>
    </div>
    <div class="card-body">
        @if(count($ranks) > 0)
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr class="bg-danger text-white">
                        <th>Ranking</th>
                        <th>Kode Alternatif</th>
                        <th>Nama Alternatif</th>
                        <th>Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ranks as $index => $rank)
                    <tr>
                        <td>
                            @if($index == 0)
                            <span class="badge badge-warning rounded-circle p-2"><i class="fas fa-crown"></i> 1</span>
                            @elseif($index == 1)
                            <span class="badge badge-secondary rounded-circle p-2">2</span>
                            @elseif($index == 2)
                            <span class="badge badge-danger rounded-circle p-2">3</span>
                            @else
                            <span class="badge badge-light rounded-circle p-2">{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td>{{ $rank['alternatif']->kode_alternatif }}</td>
                        <td>{{ $rank['alternatif']->nama_alternatif }}</td>
                        <td class="font-weight-bold">{{ number_format($rank['nilai_akhir'], 4) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info">
            Belum ada data untuk dihitung. Silahkan input Kriteria, Alternatif, dan Penilaian terlebih dahulu.
        </div>
        @endif
    </div>
</div>

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
        .btn {
            display: none !important;
        }
    }
</style>

<script>
    function exportTableToExcel(tableID, filename = 'hasil-akhir-saw.xls') {
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

        // Specify file name
        filename = filename ? filename + '.xls' : 'excel_data.xls';

        // Create download link element
        downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        if (navigator.msSaveOrOpenBlob) {
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

            // Setting the file name
            downloadLink.download = filename;

            //triggering the function
            downloadLink.click();
        }
    }
</script>
@endsection