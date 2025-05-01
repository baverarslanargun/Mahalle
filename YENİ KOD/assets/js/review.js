// review.js
document.addEventListener('DOMContentLoaded',()=>{
    const ilSelect  = document.getElementById('ilSelect'),
          ilceSelect= document.getElementById('ilceSelect'),
          mahSelect = document.getElementById('mahSelect'),
          form      = document.getElementById('reviewForm');
  
    ilSelect.onchange = ()=>{
      fetch(`/api/get_ilceler.php?il_id=${ilSelect.value}`)
        .then(r=>r.json())
        .then(data=>{
          ilceSelect.innerHTML = '<option value="">Seçiniz</option>';
          data.forEach(o=>{
            ilceSelect.innerHTML += `<option value="${o.id}">${o.isim}</option>`;
          });
        });
    };
    ilceSelect.onchange = ()=>{
      fetch(`/api/get_mahalleler_by_ilce.php?ilce_id=${ilceSelect.value}`)
        .then(r=>r.json())
        .then(data=>{
          mahSelect.innerHTML = '<option value="">Seçiniz</option>';
          data.forEach(o=>{
            mahSelect.innerHTML += `<option value="${o.id}">${o.isim}</option>`;
          });
        });
    };
  
    form.onsubmit = ev=>{
      ev.preventDefault();
      const payload = {
        mahalle_id: mahSelect.value,
        yorum:      form.yorum.value,
        puan:       {}
      };
      new FormData(form).forEach((v,k)=>{
        if(k.startsWith('puan')) {
          const id = k.match(/\d+/)[0];
          payload.puan[id] = v;
        }
      });
      fetch('/api/submit_review.php',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify(payload)
      })
      .then(r=>r.json())
      .then(json=>{
        if(json.success) alert('Teşekkürler, yorumunuz kaydedildi.');
        else alert('Hata oldu.');
        form.reset();
      });
    };
  });
  