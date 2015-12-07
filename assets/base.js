(function(target) {
  'use strict'
  function ajax(Url, Method, Contents) {
    return new Promise(function(resolve, reject) {
      const XHR = new XMLHttpRequest()
      XHR.open(Method, Url, true)
      XHR.addEventListener('load', () => {
        resolve(XHR.responseText)
      })
      XHR.addEventListener('error', reject)
      XHR.setRequestHeader('X-Auth', 'COOKIE');
      XHR.send(typeof Contents === 'object' ? JSON.stringify(Contents) : Contents)
    })
  }


  function lock(Callback) {
    let InProgress = false

    const Callable = function(Param) {
      if (!InProgress) {
        const ReturnValue = Callback.call(this, Param)
        InProgress = true

        if (ReturnValue && ReturnValue.constructor.name === 'Promise') {
          ReturnValue.then(function() {
            InProgress = false
          }, function(e) {
            console.error(e)
            InProgress = false
          })

        } else InProgress = false
      }
    }

    Callable.prototype = Callable.prototype
    return Callable
  }

  function getElements(form) {
    const elements = {}
    Array.prototype.forEach.call(form.querySelectorAll('[name]'), function(element) {
      elements[element.name] = element
    })
    return elements
  }

  function getValues(elements) {
    const values = {}
    for (const key in elements) {
      const element = elements[key]

      values[key] = element.value
    }
    return values
  }

  window.App = {lock, ajax, getElements, getValues}
})(window)
