@extends('layouts.app')

@section('content')
<div class="kt-portlet">
	<div class="kt-portlet__body  kt-portlet__body--fit">
		<div class="row row-no-padding row-col-separator-xl">
			
			<div class="col-md-12 col-lg-6 col-xl-3">
				<!--begin::Total Profit-->
				<div class="kt-widget24">
					<div class="kt-widget24__details">
						<div class="kt-widget24__info">
							<h4 class="kt-widget24__title">
					            Pengguna
					        </h4>
					        <span class="kt-widget24__desc">
					            Jumlah Pengguna
					        </span>
						</div>

						<span class="kt-widget24__stats kt-font-brand">
					        {{$pengguna}}
					    </span>	 
					</div> 

				    <div class="progress progress--sm">
						<div class="progress-bar kt-bg-brand" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
					</div>

					<div class="kt-widget24__action">
						<span class="kt-widget24__change">
							
						</span>
						<span class="kt-widget24__number">
							
					    </span>
					</div>				   			      
				</div>
				<!--end::Total Profit-->
			</div>

			<div class="col-md-12 col-lg-6 col-xl-3">
				<!--begin::New Feedbacks-->
				<div class="kt-widget24">
					<div class="kt-widget24__details">
						<div class="kt-widget24__info">
							<h4 class="kt-widget24__title">
					           Data Inventaris Umum
					        </h4>
					        <span class="kt-widget24__desc">
					            Jumlah Data
					        </span>
						</div>

						<span class="kt-widget24__stats kt-font-warning">
					       {{$inv_umum}}
					    </span>	 
					</div> 

				    <div class="progress progress--sm">
						<div class="progress-bar kt-bg-warning" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
					</div>

					<div class="kt-widget24__action">
						<span class="kt-widget24__change">
							
						</span>
						<span class="kt-widget24__number">
							
					    </span>
					</div>				   			      
				</div>				
				<!--end::New Feedbacks--> 
			</div>

			<div class="col-md-12 col-lg-6 col-xl-3">
				<!--begin::New Orders-->
				<div class="kt-widget24">
					<div class="kt-widget24__details">
						<div class="kt-widget24__info">
							<h4 class="kt-widget24__title">
					            Data Inventaris Medis
					        </h4>
					        <span class="kt-widget24__desc">
					            Jumlah Data
					        </span>
						</div>

						<span class="kt-widget24__stats kt-font-danger">
					        {{$inv_medis}}
					    </span>	 
					</div> 

				    <div class="progress progress--sm">
						<div class="progress-bar kt-bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
					</div>

					<div class="kt-widget24__action">
						<span class="kt-widget24__change">
							
						</span>
						<span class="kt-widget24__number">
						
					    </span>
					</div>				   			      
				</div>				
				<!--end::New Orders--> 
			</div>

			<div class="col-md-12 col-lg-6 col-xl-3">
				<!--begin::New Users-->
				<div class="kt-widget24">
					<div class="kt-widget24__details">
						<div class="kt-widget24__info">
							<h4 class="kt-widget24__title">
					            Seluruh Inventaris
					        </h4>
					        <span class="kt-widget24__desc">
					            Semua Inventaris
					        </span>
						</div>

						<span class="kt-widget24__stats kt-font-success">
					       {{ $total_inv }}
					    </span>	 
					</div> 

				    <div class="progress progress--sm">
						<div class="progress-bar kt-bg-success" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
					</div>

					<div class="kt-widget24__action">
						<span class="kt-widget24__change">
							
						</span>
						<span class="kt-widget24__number">
							
					    </span>
					</div>				   			      
				</div>				
				<!--end::New Users--> 
			</div>

		</div>
	</div>
</div>
@endsection
<script>
     jQuery(document).ready(function() {
        $('.progress').hide()
        });
</script>
