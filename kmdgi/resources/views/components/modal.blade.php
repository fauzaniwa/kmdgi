@props([
    'id',
    'type' => 'info', // Default cadangan jika tidak dioper dari tombol
    'buttonLayout' => 'horizontal', // Opsi default tata letak tombol
])

<div id="{{ $id }}" class="fixed inset-0 z-[100] hidden opacity-0 transition-opacity duration-300 ease-in-out modal-container" data-default-type="{{ $type }}">
    
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeModal('{{ $id }}')"></div>

    <div class="hidden md:flex absolute inset-0 items-center justify-center pointer-events-none">
        <div class="bg-white rounded-[2rem] p-8 w-full max-w-[22rem] mx-4 shadow-2xl transform scale-95 transition-transform duration-300 ease-in-out desktop-modal-box pointer-events-auto">
            
            <div class="modal-icon-wrapper w-10 h-10 rounded-full flex items-center justify-center mb-5 border border-white shadow-sm">
                <svg class="modal-icon-svg w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"></svg>
            </div>

            <h3 class="modal-title text-[17px] font-bold text-slate-900 leading-tight mb-2.5"></h3>
            <p class="modal-message text-[13px] text-slate-500 leading-relaxed"></p>

            <div class="mt-8 flex items-center gap-3 @if($buttonLayout === 'vertical') flex-col-reverse @endif">
                <button type="button" onclick="closeModal('{{ $id }}')}" class="modal-secondary-btn w-full flex-1 bg-white border border-slate-200 text-slate-600 font-bold py-3.5 px-4 rounded-[14px] hover:bg-slate-50 transition-colors text-sm hidden"></button>
                <button type="button" class="modal-primary-btn w-full flex-1 font-bold py-3.5 px-4 rounded-[14px] transition-colors text-sm shadow-sm"></button>
            </div>
        </div>
    </div>

    <div class="flex md:hidden absolute bottom-0 inset-x-0 justify-end flex-col pointer-events-none">
        <div class="bg-white rounded-t-[2.5rem] px-6 pt-4 pb-8 shadow-2xl transform translate-y-full transition-transform duration-300 ease-in-out mobile-bottom-sheet pointer-events-auto">
            
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-6"></div>
            
            <div class="flex items-start gap-4 mb-6">
                <div class="modal-icon-wrapper w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 border border-white shadow-sm">
                    <svg class="modal-icon-svg w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"></svg>
                </div>
                <div>
                    <h3 class="modal-title text-lg font-bold text-slate-900 leading-tight mb-1.5"></h3>
                    <p class="modal-message text-sm text-slate-500 leading-relaxed"></p>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <button type="button" class="modal-primary-btn w-full font-bold py-4 rounded-2xl text-sm shadow-sm"></button>
                <button type="button" onclick="closeModal('{{ $id }}')" class="modal-secondary-btn w-full bg-slate-50 text-slate-600 font-bold py-4 rounded-2xl text-sm border border-slate-100 hidden"></button>
            </div>
            
        </div>
    </div>

</div>