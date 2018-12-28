<div id="examEditModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Exam</h5>        
      </div>
      <div class="modal-body">
        <form areaAdd method="post">
          <input type="hidden" name="aftoken" value="<? echo $data->SessionData->AntiForgeryToken; ?>">
          <input type="hidden" name="subjectId" value="<? echo $subjectId; ?>">
          <input type="text" name="title" class="from-control p-1 border mb-3 w-100 rounded" placeholder="Exam title" autocomplete="off">
          <input type="text" name="date" class="from-control p-1 border mb-3 w-100 rounded" placeholder="Exam date">
          <textarea name="description" maxlength="<? echo $GLOBALS["config"]["validate"]["description"]["max"]; ?>" 
            cols="30" rows="8" class="form-control p-1 border mb-3 rounded" placeholder="Exam description (optional)"></textarea>
          <input type="number" name="grade" class="form-control p-1 border mb-3 rounded" placeholder="Grade" step="0.25"
            min="<? echo $GLOBALS["config"]["validate"]["grade"]["min"]; ?>" max="<? echo $GLOBALS["config"]["validate"]["grade"]["max"]; ?>">
          <input type="number" name="grading" class="from-control p-1 border mb-3 w-100 rounded" placeholder="Exam grading in %" 
            min="<? echo $GLOBALS["config"]["validate"]["grading"]["min"]; ?>" max="<? echo $GLOBALS["config"]["validate"]["grading"]["max"]; ?>" title="Exam grading in %">
        </form>
      </div>
      <div class="modal-footer">
        <button cancel type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
        <button edit type="button" class="btn btn-secondary">Add</button>
      </div>
    </div>
  </div>
</div>
<script>
  let subjectModal = (function () {    
    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    const modalTitleElem = document.querySelector('.modal-title');
    const descriptionElem = document.querySelector('textarea[name="description"]');
    const titleElem = document.querySelector('input[name="title"]');
    const dateElem = document.querySelector('input[type="text"][name="date"]');
    const gradingElem = document.querySelector('input[type="number"][name="grading"]');
    const gradeElem = document.querySelector('input[type="number"][name="grade"]');
    const submitBtn = document.querySelector('button[edit]');
    const cancelBtn = document.querySelector('button[cancel]');
    const form = document.querySelector('form[areaAdd]');
    const titleConMax = <? echo $GLOBALS["config"]["validate"]["title"]["max"]; ?>;    
    const titleConMin = <? echo $GLOBALS["config"]["validate"]["title"]["min"]; ?>;    
    const descriptionConMax = <? echo $GLOBALS["config"]["validate"]["description"]["max"]; ?>;
    const gradingConMax = <? echo $GLOBALS["config"]["validate"]["grading"]["max"]; ?>;
    const gradingConMin = <? echo $GLOBALS["config"]["validate"]["grading"]["min"]; ?>;
    const gradeConMax = <? echo $GLOBALS["config"]["validate"]["grade"]["max"]; ?>;    
    const gradeConMin = <? echo $GLOBALS["config"]["validate"]["grade"]["min"]; ?>;
    const yearConMax = <? echo $GLOBALS["config"]["validate"]["date"]["year"]["max"]; ?>;
    const yearConMin = <? echo $GLOBALS["config"]["validate"]["date"]["year"]["max"]; ?>;
    const redBorder = 'border-danger';
    let datePicker;
    let enableAutocompleteDescription = false;

    document.addEventListener('DOMContentLoaded', () => {       
        titleElem.addEventListener('input', () => {
            titleElem.classList.remove(redBorder);
            if (enableAutocompleteDescription === true) {
                let title = titleElem.value;
                if (title.length > 0) {
                    const defaultDescription = 'Description of subject ';
                    descriptionElem.textContent = defaultDescription + title;   
                    descriptionElem.classList.remove(redBorder);
                } else {
                    descriptionElem.textContent = '';
                }          
            }        
        });  

        descriptionElem.addEventListener('input', () => {
            descriptionElem.classList.remove(redBorder);
        });

        gradingElem.addEventListener('input', () => {
            gradingElem.classList.remove(redBorder);
        });

        gradeElem.addEventListener('input', () => {
            gradeElem.classList.remove(redBorder);
        });

        submitBtn.addEventListener('click', () => {
            if (validate() === true) {          
                form.submit();
            }
        });   

        $('#examEditModal').on('hide.bs.modal', clear);
    });

    function validate()
    {
      const titleContent = titleElem.value.length;
      const descriptionContent = descriptionElem.textLength;
      const gradingContent = gradingElem.value;
      const gradeContent = gradeElem.value;
      const dateContentParts = dateElem.value.split('.');      
      let titleValid, descriptionValid, gradingValid, gradeValid, dateValid;
      if (titleContent > titleConMin && titleContent <= titleConMax) {
          titleValid = true;          
      } else {
        titleValid = false;
        setErrorState(titleElem);
      }
      if (descriptionContent > 0) {
        if (descriptionContent <= descriptionConMax) {
            descriptionValid = true;
        } else {
            descriptionValid = false;
            setErrorState(descriptionElem);
        }          
      } else {
          descriptionValid = true;
      }
      if (gradingContent > gradingConMin && gradingContent <= gradingConMax) {
        gradingValid = true;
      } else{
        gradingValid = false;
        setErrorState(gradingElem);
      }
      if (gradeContent >= gradeConMin && gradeContent <= gradeConMax) {
          gradeValid = true;
      } else {
          gradeValid = false;
          setErrorState(gradeElem);
      }
      if (isValidDate(dateContentParts)) {
          dateValid = true;
      } else {
          dateValid = false;
          setErrorState(dateElem);
      }
      
      if (titleValid && descriptionValid && gradingValid && gradeValid && dateValid) {
        return true;
      } else {
          return false;
      }
    }

    function clear()
    {
      form.reset();
      titleElem.classList.remove(redBorder);
      descriptionElem.classList.remove(redBorder);
      descriptionElem.textContent = '';
      gradingElem.classList.remove(redBorder);
      gradeElem.classList.remove(redBorder);
      datePicker.remove();
    }

    function launchModal(newArea = true, data)
    {        
        if (newArea === false) {
            const subjectIdElem = '<input type="hidden" name="examId" value="' + data.Id + '" />'
            form.insertAdjacentHTML('afterbegin', subjectIdElem);
            form.setAttribute('action', '/exam-edit');
            modalTitleElem.textContent = 'Edit Exam';
            submitBtn.textContent = 'Save Changes';
            titleElem.value = data.Title;
            descriptionElem.textContent = data.Description;
            gradingElem.value = Math.floor(data.Grading * 100);
            gradeElem.value = data.Grade;      
            const uf = new UnixFormatter(data.Date);
            dateElem.value = uf.numberFormat;  
            dateElem.setAttribute('title', uf.textFormat);
            datePicker = datepicker(dateElem, {
                onSelect: function(instance) {
                    const dateElemjQ = $('input[type="text"][name="date"]');
                    dateElemjQ.tooltip('dispose');
                    dateElem.setAttribute('title', getStringFormatDate(instance.dateSelected));
                    dateElemjQ.tooltip();
                },
                formatter: (el, date, instance) => {
                    el.value = getNumberFormatDate(date);
                }                
            });
            datePicker.setDate(new Date(uf.year, uf.monthNumber - 1, uf.dateDay), true);            
            enableAutocompleteDescription = false;        
        } else {        
            form.setAttribute('action', '/exam-add');
            modalTitleElem.textContent = 'Add Exam';
            submitBtn.textContent = 'Add';
            gradingElem.value = "100";
            datePicker = datepicker(dateElem, {
                onSelect: function(instance) {
                    const dateElemjQ = $('input[type="text"][name="date"]');
                    dateElemjQ.tooltip('dispose');
                    dateElem.setAttribute('title', getStringFormatDate(instance.dateSelected));
                    dateElemjQ.tooltip();
                },
                formatter: (el, date, instance) => {
                    el.value = getNumberFormatDate(date);
                }                
            });
            
            enableAutocompleteDescription = false;
        }
        datePicker.setMax(new Date());
        datePicker.setMin(new Date(yearConMin));
        $('#examEditModal').modal();
    }

    function setErrorState(elem)
    {
        elem.classList.add(redBorder);
    }

    function isValidDate(dateParts)
    {
        dateParts.forEach(e => {
            if (isNaN(parseInt(e))) {
                return false;
            }
        });        
        let day, month, year;
        day = dateParts[0];
        month = dateParts[1] - 1;
        year = dateParts[2];
        const date = new Date(year, month, day);
        const dateMin = new Date(yearConMin);
        const dateMax = new Date();
        if (!isNaN(date) && date.getTime() >= dateMin.getTime() && date.getTime() <= dateMax.getTime()) {
            return true;
        } else {
            return false;
        }
    }

    function getNumberFormatDate(date)
    {
        return `${date.getDate() < 10 ? '0' + date.getDate() : date.getDate()}.${(date.getMonth() + 1) < 10 ? '0' + (date.getMonth() + 1) : (date.getMonth() + 1)}.${date.getFullYear()}`;
    }

    function getStringFormatDate(date)
    {
        return `${days[date.getDay()]}, ${date.getDate()}. ${months[date.getMonth()]} ${date.getFullYear()}`;
    }

    return {
      showModal: launchModal   
    }
  })();
</script>