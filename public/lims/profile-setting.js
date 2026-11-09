// Halaman Setting akun: ubah kata sandi + tanda tangan (dipakai di report PDF)

// ===== Ubah kata sandi =====
$('#btn_update_password').on('click', function () {
    postCallback('updateProfilePassword', {
        current_password: $('#current_password').val(),
        password: $('#new_password').val(),
        password_confirmation: $('#new_password_confirmation').val()
    }, function (response) {
        swal.close()
        toastr['success'](response.meta.message)
        $('#current_password, #new_password, #new_password_confirmation').val('')
        $('.message_error').html('')
    })
})

// ===== Tanda tangan (canvas pad) =====
var signatureCanvas = document.getElementById('signature_pad')
var signatureCtx = signatureCanvas.getContext('2d')
var signatureDrawing = false
var signatureDirty = false

function signatureResize() {
    // simpan goresan saat resize dengan menggambar ulang dari data URL
    var prev = signatureDirty ? signatureCanvas.toDataURL('image/png') : null
    var ratio = window.devicePixelRatio || 1
    signatureCanvas.width = signatureCanvas.offsetWidth * ratio
    signatureCanvas.height = signatureCanvas.offsetHeight * ratio
    signatureCtx.scale(ratio, ratio)
    signatureCtx.lineWidth = 2
    signatureCtx.lineCap = 'round'
    signatureCtx.lineJoin = 'round'
    signatureCtx.strokeStyle = '#1e293b'
    if (prev) {
        var img = new Image()
        img.onload = function () {
            signatureCtx.drawImage(img, 0, 0, signatureCanvas.offsetWidth, signatureCanvas.offsetHeight)
        }
        img.src = prev
    }
}

function signaturePos(e) {
    var rect = signatureCanvas.getBoundingClientRect()
    var point = e.touches && e.touches.length ? e.touches[0] : e
    return { x: point.clientX - rect.left, y: point.clientY - rect.top }
}

function signatureStart(e) {
    e.preventDefault()
    signatureDrawing = true
    signatureDirty = true
    $('.signature-hint').hide()
    var pos = signaturePos(e)
    signatureCtx.beginPath()
    signatureCtx.moveTo(pos.x, pos.y)
}

function signatureMove(e) {
    if (!signatureDrawing) return
    e.preventDefault()
    var pos = signaturePos(e)
    signatureCtx.lineTo(pos.x, pos.y)
    signatureCtx.stroke()
}

function signatureEnd() {
    signatureDrawing = false
}

signatureCanvas.addEventListener('mousedown', signatureStart)
signatureCanvas.addEventListener('mousemove', signatureMove)
window.addEventListener('mouseup', signatureEnd)
signatureCanvas.addEventListener('touchstart', signatureStart, { passive: false })
signatureCanvas.addEventListener('touchmove', signatureMove, { passive: false })
signatureCanvas.addEventListener('touchend', signatureEnd)
window.addEventListener('resize', signatureResize)
signatureResize()

$('#btn_clear_signature').on('click', function () {
    signatureCtx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height)
    signatureDirty = false
    $('.signature-hint').show()
})

$('#btn_save_signature').on('click', function () {
    if (!signatureDirty) {
        toastr['warning']('Gambar tanda tangan dulu sebelum menyimpan')
        return
    }
    var dataUrl = signatureCanvas.toDataURL('image/png')
    postCallback('updateProfileSignature', { signature: dataUrl }, function (response) {
        swal.close()
        toastr['success'](response.meta.message)
        $('#signature_current').attr('src', dataUrl).show()
        $('#signature_current_empty').hide()
        $('.message_error').html('')
    })
})
