import Swal from 'sweetalert2'

// Detecta modo escuro
const isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches

// Estilo base dos modais
export const baseConfig = {
  confirmButtonColor: '#4ade80', // green-400
  cancelButtonColor: '#f87171',  // red-400
  denyButtonColor: '#ffd166',   // red-400
  background: isDarkMode ? '#1f2937' : '#fff', // gray-800 ou branco
  color: isDarkMode ? '#f3f4f6' : '#374151',   // gray-100 ou gray-700
  customClass: {
    title: 'text-lg font-semibold',
    confirmButton: 'px-4 py-2 rounded-md text-sm',
    cancelButton: 'px-4 py-2 rounded-md text-sm',
    denyButton: 'px-4 py-2 rounded-md text-sm',
  }
}

// 🔄 Loading (sem botão)
export const showLoading = async (title = 'Carregando...') => {
  return Swal.fire({
    ...baseConfig,
    title,
    allowOutsideClick: false,
    allowEscapeKey: false,
    showConfirmButton: false,
    didOpen: () => {
      Swal.showLoading()
    }
  })
}

// ✅ Sucesso
export const showSuccess = async (title = 'Sucesso', text = '') => {
  return Swal.fire({
    ...baseConfig,
    icon: 'success',
    title,
    text,
    timer: 2000,
    showConfirmButton: false,
    toast: true,
    position: 'top-end'
  })
}

// ❌ Erro
export const showError = async (title = 'Erro', text = 'Algo deu errado.') => {
  return Swal.fire({
    ...baseConfig,
    icon: 'error',
    title,
    text,
    confirmButtonText: 'Fechar'
  })
}

// ℹ️ Informação
export const showInfo = async (title = 'Informação', text = '') => {
  return Swal.fire({
    ...baseConfig,
    icon: 'info',
    title,
    text,
    timer: 2000,
    showConfirmButton: false,
    toast: true,
    position: 'top-end'
  })
}

export const close = async () => {
  Swal.close();
}

// ⚠️ Confirmação
export const showConfirm = async (title = 'Tem certeza?', text = 'Essa ação não pode ser desfeita.') => {
  const result = await Swal.fire({
    ...baseConfig,
    icon: 'warning',
    title,
    text,
    showCancelButton: true,
    confirmButtonText: 'Sim',
    cancelButtonText: 'Cancelar'
  })

  return result.isConfirmed
}

export const showPixPaymentModal = async (qrCodeUrl = '/assets/img/PixQRCode.jpg') => {
  const result = await Swal.fire({
    ...baseConfig,
    title: 'Pagamento via Pix',
    html: `
      <p>Escaneie o QR Code para pagar:</p>
      <img src="${qrCodeUrl}" alt="QR Code Pix" style="width: 250px; margin: 10px auto;" />
    `,
    showCancelButton: true,
    confirmButtonText: 'Confirmar Pagamento',
    cancelButtonText: 'Cancelar'
  });

  return result.isConfirmed;
};

export const showCashPaymentModal = async (total = 0) => {
  const result = await Swal.fire({
    ...baseConfig,
    title: 'Pagamento em Dinheiro',
    html: `
      <div class="flex flex-col items-center">
      <p class="mb-2 text-gray-700">Total do pedido: <strong>R$ ${parseFloat(total).toFixed(2)}</strong></p>
      <input
        type="number"
        id="valorPago"
        step="0.01"
        class="swal2-input block rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
        placeholder="Valor pago pelo cliente"
      >
      <p id="troco" class="mt-2 text-sm text-gray-600">Troco: R$ 0,00</p>
      </div>
    `,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText: 'Confirmar Pagamento',
    cancelButtonText: 'Cancelar',
    didOpen: () => {
      const input = document.getElementById('valorPago');
      const trocoEl = document.getElementById('troco');
      const confirmButton = Swal.getConfirmButton();
      confirmButton.disabled = true; // começa desabilitado

      input.addEventListener('input', () => {
        const valorPago = parseFloat(input.value) || 0;
        const troco = valorPago - total;

        trocoEl.textContent = `Troco: R$ ${troco > 0 ? troco.toFixed(2) : '0,00'}`;
        confirmButton.disabled = valorPago < total; // habilita/desabilita conforme valor
      });
    },
    preConfirm: () => {
      const input = document.getElementById('valorPago');
      const valorPago = parseFloat(input.value);
      if (isNaN(valorPago) || valorPago < total) {
        Swal.showValidationMessage('O valor pago deve ser maior ou igual ao total.');
        return false;
      }
      const troco = valorPago - total;
      return { valorPago, troco };
    }
  });

  if (result.isConfirmed) {
    return result.value; // { valorPago, troco }
  }

  return null;
};