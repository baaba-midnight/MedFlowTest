<div id="modal" class="fixed inset-0 flex items-center justify-center z-50 transition-opacity duration-300 opacity-0 pointer-events-none">
    <div class="bg-white rounded-md shadow-lg max-w-4xl w-full h-[90vh] flex flex-col m-4">
        <div class="flex justify-between items-center p-6 border-b">
            <h2 id="modal-title" class="text-2xl font-bold"></h2>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeModal()">
                Close
            </button>
        </div>
        <div id="modal-content" class="flex-1 p-6 overflow-y-auto">
        </div>
    </div>
</div>