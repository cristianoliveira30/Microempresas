import Swal from 'sweetalert2'

// Detecta modo escuro
const isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches

// Estilo base dos modais
const baseConfig = {
  confirmButtonColor: '#4ade80', // green-400
  cancelButtonColor: '#f87171',  // red-400
  background: isDarkMode ? '#1f2937' : '#fff', // gray-800 ou branco
  color: isDarkMode ? '#f3f4f6' : '#374151',   // gray-100 ou gray-700
  customClass: {
    title: 'text-lg font-semibold',
    confirmButton: 'px-4 py-2 rounded-md text-sm',
    cancelButton: 'px-4 py-2 rounded-md text-sm',
  }
}

// 🔄 Loading (sem botão)
export const showLoading = (title = 'Carregando...') => {
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
export const showSuccess = (title = 'Sucesso', text = '') => {
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
export const showError = (title = 'Erro', text = 'Algo deu errado.') => {
  return Swal.fire({
    ...baseConfig,
    icon: 'error',
    title,
    text,
    confirmButtonText: 'Fechar'
  })
}

// ℹ️ Informação
export const showInfo = (title = 'Informação', text = '') => {
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
