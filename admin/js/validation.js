/*
* Really easy field validation with Prototype
* http://tetlaw.id.au/view/javascript/really-easy-field-validation
* Andrew Tetlaw
* Version 1.5.4.1 (2007-01-05)
*
* Copyright (c) 2007 Andrew Tetlaw
* Permission is hereby granted, free of charge, to any person
* obtaining a copy of this software and associated documentation
* files (the "Software"), to deal in the Software without
* restriction, including without limitation the rights to use, copy,
* modify, merge, publish, distribute, sublicense, and/or sell copies
* of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be
* included in all copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
* EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
* MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
* NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS
* BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN
* ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
* CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*
*/
var Validator = Class.create();

Validator.prototype = {
	initialize : function(className, error, test, options) {
		if(typeof test == 'function'){
			this.options = $H(options);
			this._test = test;
		} else {
			this.options = $H(test);
			this._test = function(){return true};
		}
		this.error = error || 'Validation failed.';
		this.className = className;
	},
	test : function(v, elm) {
		return (this._test(v,elm) && this.options.all(function(p){
			return Validator.methods[p.key] ? Validator.methods[p.key](v,elm,p.value) : true;
		}));
	}
}
Validator.methods = {
	pattern : function(v,elm,opt) {return Validation.get('IsEmpty').test(v) || opt.test(v)},
	minLength : function(v,elm,opt) {return v.length >= opt},
	maxLength : function(v,elm,opt) {return v.length <= opt},
	min : function(v,elm,opt) {return v >= parseFloat(opt)},
	max : function(v,elm,opt) {return v <= parseFloat(opt)},
	notOneOf : function(v,elm,opt) {return $A(opt).all(function(value) {
		return v != value;
	})},
	oneOf : function(v,elm,opt) {return $A(opt).any(function(value) {
		return v == value;
	})},
	is : function(v,elm,opt) {return v == opt},
	isNot : function(v,elm,opt) {return v != opt},
	equalToField : function(v,elm,opt) {return v == $F(opt)},
	notEqualToField : function(v,elm,opt) {return v != $F(opt)},
	include : function(v,elm,opt) {return $A(opt).all(function(value) {
		return Validation.get(value).test(v,elm);
	})}
}

var Validation = Class.create();

Validation.prototype = {
	initialize : function(form, options){
		this.options = Object.extend({
			onSubmit : true,
			stopOnFirst : false,
			immediate : false,
			focusOnError : true,
			useTitles : false,
			onFormValidate : function(result, form) {},
			onElementValidate : function(result, elm) {}
		}, options || {});
		this.form = $(form);
		if(this.options.onSubmit) Event.observe(this.form,'submit',this.onSubmit.bind(this),false);
		if(this.options.immediate) {
			var useTitles = this.options.useTitles;
			var callback = this.options.onElementValidate;
			Form.getElements(this.form).each(function(input) { // Thanks Mike!
				Event.observe(input, 'blur', function(ev) { Validation.validate(Event.element(ev),{useTitle : useTitles, onElementValidate : callback}); });
			});
		}
	},
	onSubmit :  function(ev){
		if(!this.validate()) Event.stop(ev);
	},
	validate : function() {
		var result = false;
		var useTitles = this.options.useTitles;
		var callback = this.options.onElementValidate;
		if(this.options.stopOnFirst) {
			result = Form.getElements(this.form).all(function(elm) { return Validation.validate(elm,{useTitle : useTitles, onElementValidate : callback}); });
		} else {
			result = Form.getElements(this.form).collect(function(elm) { return Validation.validate(elm,{useTitle : useTitles, onElementValidate : callback}); }).all();
		}
		if(!result && this.options.focusOnError) {
			Form.getElements(this.form).findAll(function(elm){return $(elm).hasClassName('validation-failed')}).first().focus()
		}
		this.options.onFormValidate(result, this.form);
		return result;
	},
	reset : function() {
		Form.getElements(this.form).each(Validation.reset);
	}
}

Object.extend(Validation, {
	validate : function(elm, options){
		options = Object.extend({
			useTitle : false,
			onElementValidate : function(result, elm) {}
		}, options || {});
		elm = $(elm);
		var cn = elm.classNames();
		return result = cn.all(function(value) {
			var test = Validation.test(value,elm,options.useTitle);
			options.onElementValidate(test, elm);
			return test;
		});
	},
	test : function(name, elm, useTitle) {
		var v = Validation.get(name);
		var prop = '__advice'+name.camelize();
		try {
		if(Validation.isVisible(elm) && !v.test($F(elm), elm)) {
			if(!elm[prop]) {
				var advice = Validation.getAdvice(name, elm);
				if(advice == null) {
					var errorMsg = useTitle ? ((elm && elm.title) ? elm.title : v.error) : v.error;
					advice = '<div class="validation-advice" id="advice-' + name + '-' + Validation.getElmID(elm) +'" style="display:none">' + errorMsg + '</div>'
					switch (elm.type.toLowerCase()) {
						case 'checkbox':
						case 'radio':
							var p = elm.parentNode;
							if(p) {
								new Insertion.Bottom(p, advice);
							} else {
								new Insertion.After(elm, advice);
							}
							break;
						default:
							new Insertion.After(elm, advice);
				    }
					advice = Validation.getAdvice(name, elm);
				}
				if(typeof Effect == 'undefined') {
					advice.style.display = 'block';
				} else {
					new Effect.Appear(advice, {duration : 1 });
				}
			}
			elm[prop] = true;
			elm.removeClassName('validation-passed');
			elm.addClassName('validation-failed');
			return false;
		} else {
			var advice = Validation.getAdvice(name, elm);
			if(advice != null) advice.hide();
			elm[prop] = '';
			elm.removeClassName('validation-failed');
			elm.addClassName('validation-passed');
			return true;
		}
		} catch(e) {
			throw(e)
		}
	},
	isVisible : function(elm) {
		while(elm.tagName != 'BODY') {
			if(!$(elm).visible()) return false;
			elm = elm.parentNode;
		}
		return true;
	},
	getAdvice : function(name, elm) {
		return $('advice-' + name + '-' + Validation.getElmID(elm)) || $('advice-' + Validation.getElmID(elm));
	},
	getElmID : function(elm) {
		return elm.id ? elm.id : elm.name;
	},
	reset : function(elm) {
		elm = $(elm);
		var cn = elm.classNames();
		cn.each(function(value) {
			var prop = '__advice'+value.camelize();
			if(elm[prop]) {
				var advice = Validation.getAdvice(value, elm);
				advice.hide();
				elm[prop] = '';
			}
			elm.removeClassName('validation-failed');
			elm.removeClassName('validation-passed');
		});
	},
	add : function(className, error, test, options) {
		var nv = {};
		nv[className] = new Validator(className, error, test, options);
		Object.extend(Validation.methods, nv);
	},
	addAllThese : function(validators) {
		var nv = {};
		$A(validators).each(function(value) {
				nv[value[0]] = new Validator(value[0], value[1], value[2], (value.length > 3 ? value[3] : {}));
			});
		Object.extend(Validation.methods, nv);
	},
	get : function(name) {
		return  Validation.methods[name] ? Validation.methods[name] : Validation.methods['_LikeNoIDIEverSaw_'];
	},
	methods : {
		'_LikeNoIDIEverSaw_' : new Validator('_LikeNoIDIEverSaw_','',{})
	}
});

Validation.add('IsEmpty', '', function(v) {
				return  ((v == null) || (v.length == 0)); // || /^\s+$/.test(v));
			});

Validation.addAllThese([
	['required', 'campo&#160;requerido', function(v) {
				ret = !Validation.get('IsEmpty').test(v);
				return ret;
			}],
	['required-condition', 'campo&#160;requerido', function(v) {
				if(document.getElementById('frm_alta_tope').value !="") {
					ret = !Validation.get('IsEmpty').test(v);
					return ret;
				} else
					return true;
			}],
	['validate-hours', 'hora inválida', function(v) {
				return (v>=0 && v<=24);
			}],
	['validate-minutes', 'minutos inválidos', function(v) {
				return (v>=0 && v<=60);
			}],
	['validate-len5', 'ingrese al menos 5 caracteres', function(v) {
				return (v.length >= 5 );
			}],
	['validate-len9', 'ingrese como maximo 9 digitos', function(v) {
				var ret = (v.length <= 9 );
				return ret;
			}],
	['validate-number', 'ingrese un numero', function(v) {
				return Validation.get('IsEmpty').test(v) || (!isNaN(v) && !/^\s+$/.test(v));
			}],
	['validate-number-mayor', 'ingrese un número mayor que chances desde', function(v) {
				f = $F('frm_alta_cdesde');
				return ( f <= v);
			}],
	['validate-digits', 'ingrese solo dígitos', function(v) {
				var ret = Validation.get('IsEmpty').test(v) || !/[^\d]/.test(v);
				return ret;
			}],
	['validate-cuit', 'formato: 11 digitos sin guiones', function(v) {
				var ret = Validation.get('IsEmpty').test(v) || !/[^\d]/.test(v);
				return ret;
			}],
	['validate-alpha', 'ingrese solo letras (a-z)', function (v) {
				return Validation.get('IsEmpty').test(v) ||  /^[a-zA-Z]+$/.test(v)
			}],
	['validate-alpha2', 'ingrese solo letras (a-z)', function (v) {
				return Validation.get('IsEmpty').test(v) ||  /^[a-z A-Z]+$/.test(v)
			}],
	['validate-alphanum', 'solo letras (a-z) o digitos (0-9)', function(v) {
				return Validation.get('IsEmpty').test(v) ||  !/\W/.test(v)
			}],
	['validate-date', 'ingrese una fecha válida', function(v) {
				var test = new Date(v);
				return Validation.get('IsEmpty').test(v) || !isNaN(test);
			}],
	['validate-email', 'ingrese un email válido', function (v) {
				return Validation.get('IsEmpty').test(v) || /\w{1,}[@][\w\-]{1,}([.]([\w\-]{1,})){1,3}$/.test(v)
			}],
	['validate-url', 'ingrese una url válida', function (v) {
				return Validation.get('IsEmpty').test(v) || /^(http|https|ftp):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?/i.test(v)
			}],
	['validate-date-au', 'formato dd/mm/yyyy', function(v) {
				if(Validation.get('IsEmpty').test(v)) return true;
				//var regex = /^(\d{2})\/(\d{2})\/(\d{4})$/;
				var regex = /^([1-9]|0[1-9]|[12][0-9]|3[01])\D([1-9]|0[1-9]|1[012])\D(19[0-9][0-9]|20[0-9][0-9])$/;
				if(!regex.test(v)) return false;
				var d = new Date(v.replace(regex, '$2/$1/$3'));
				return ( parseInt(RegExp.$2, 10) == (1+d.getMonth()) ) &&
							(parseInt(RegExp.$1, 10) == d.getDate()) &&
							(parseInt(RegExp.$3, 10) == d.getFullYear() );
			}],
	['validate-date-past', 'fecha menor a hoy', function(v) {
				if(Validation.get('IsEmpty').test(v)) return true;
				var regex = /^([1-9]|0[1-9]|[12][0-9]|3[01])\D([1-9]|0[1-9]|1[012])\D(19[0-9][0-9]|20[0-9][0-9])$/;
				if(!regex.test(v)) return false;
				var d = new Date(v.replace(regex, '$2/$1/$3'));
				var n = new Date();
				return ( d < n);
			}],
	['validate-date-future', 'fecha mayor o igual a hoy', function(v) {
				if(Validation.get('IsEmpty').test(v)) return true;
				var regex = /^([1-9]|0[1-9]|[12][0-9]|3[01])\D([1-9]|0[1-9]|1[012])\D(19[0-9][0-9]|20[0-9][0-9])$/;
				if(!regex.test(v)) return false;
				var d = new Date(v.replace(regex, '$2/$1/$3'));
				var n = new Date();
				d.setHours(1);
				n.setHours(0);
				return ( d >= n);
			}],
	['validate-date-60-required', 'no anterior a 60 días ni fechas mayores a hoy', function(v) {
				if(Validation.get('IsEmpty').test(v)) return true;
				var regex = /^([1-9]|0[1-9]|[12][0-9]|3[01])\D([1-9]|0[1-9]|1[012])\D(19[0-9][0-9]|20[0-9][0-9])$/;
				if(!regex.test(v)) return false;
				var d = new Date(v.replace(regex, '$2/$1/$3'));
				var today = new Date();
				var n = new Date();
				n.setDate(n.getDate()-60);
				d.setHours(1);
				n.setHours(0);
				return ( d > n && d <= today);
			}],
	['validate-date-mayor', 'fecha hasta mayor a desde', function(t) {
		var regex = /^([1-9]|0[1-9]|[12][0-9]|3[01])\D([1-9]|0[1-9]|1[012])\D(19[0-9][0-9]|20[0-9][0-9])$/;
		f = $F('frm_alta_fdesde');
		if(!regex.test(f)) return false;
		if(!regex.test(t)) return false;
		var d = new Date(f.replace(regex, '$2/$1/$3'));
		var h = new Date(t.replace(regex, '$2/$1/$3'));
		h.setHours(0);
		d.setHours(0);
		return ( d < h);
			}],
	['validate-igual', 'el reingreso de contraseña debe ser igual a la contraseña', function(t) {
		f = $F('contrasenia');
		return ( f == t);
			}],
	['validate-date-mayor2', 'fecha hasta mayor a desde', function(t) {
			var regex = /^([1-9]|0[1-9]|[12][0-9]|3[01])\D([1-9]|0[1-9]|1[012])\D(19[0-9][0-9]|20[0-9][0-9])$/;
			f = $F('frm_alta_fecha');
			if(!regex.test(f)) return false;
			if(!regex.test(t)) return false;
			var d = new Date(f.replace(regex, '$2/$1/$3'));
			var h = new Date(t.replace(regex, '$2/$1/$3'));
			h.setHours(1);
			d.setHours(0);
			return ( d <= h);
		}],
	['validate-date-mayor3', 'fecha hasta mayor a desde', function(t) {
				var regex = /^([1-9]|0[1-9]|[12][0-9]|3[01])\D([1-9]|0[1-9]|1[012])\D(19[0-9][0-9]|20[0-9][0-9])$/;
				f = $F('filtro_desde');
				if(!regex.test(f)) return false;
				if(!regex.test(t)) return false;
				var d = new Date(f.replace(regex, '$2/$1/$3'));
				var h = new Date(t.replace(regex, '$2/$1/$3'));
				h.setHours(1);
				d.setHours(0);
				return ( d <= h);
			}],
	['validate-date-mayor4', 'fecha hasta mayor a desde', function(t) {
				var regex = /^([1-9]|0[1-9]|[12][0-9]|3[01])\D([1-9]|0[1-9]|1[012])\D(19[0-9][0-9]|20[0-9][0-9])$/;
				f = $F('desde');
				if(!regex.test(f)) return false;
				if(!regex.test(t)) return false;
				var d = new Date(f.replace(regex, '$2/$1/$3'));
				var h = new Date(t.replace(regex, '$2/$1/$3'));
				h.setHours(1);
				d.setHours(0);
				return ( d <= h);
			}],
	['validate-currency-dollar', 'Please enter a valid $ amount. For example $100.00 .', function(v) {
				// [$]1[##][,###]+[.##]
				// [$]1###+[.##]
				// [$]0.##
				// [$].##
				return Validation.get('IsEmpty').test(v) ||  /^\$?\-?([1-9]{1}[0-9]{0,2}(\,[0-9]{3})*(\.[0-9]{0,2})?|[1-9]{1}\d*(\.[0-9]{0,2})?|0(\.[0-9]{0,2})?|(\.[0-9]{1,2})?)$/.test(v)
			}],
	['validate-selection', 'seleccione una opción', function(v,elm){
				return elm.options ? elm.selectedIndex > 0 : !Validation.get('IsEmpty').test(v);
			}],
	['validate-one-required', 'selecione una opción', function (v,elm) {
				var p = elm.parentNode;
				var options = p.getElementsByTagName('INPUT');
				return $A(options).any(function(elm) {
					return $F(elm);
				});
			}],
	['validate-one-required-text', 'Ingrese al menos uno de los campos', function (v,elm) {
				var p = elm.parentNode;
				var options = p.getElementsByTagName('INPUT');
				return $A(options).any(function(elm) {
					return $F(elm);
				});
			}],
	['validate-diagnostico-required', 'Debe seleccionar un diagnostico del sistema', function (v,elm) {
				return $F('frm_alta_diagnostico');
			}],
	['validate-paciente-required', 'Debe seleccionar un paciente del sistema', function (v,elm) {
				return $F('frm_alta_paciente');
			}],
	['validate-stock-alta', 'este stock debe ser menor al deseado', function(e) {
				d = $F('frm_indicador_deseado');
				return ( parseInt(e) < parseInt(d));
			}],
	['validate-stock-edit', 'este stock debe ser menor al deseado', function(e) {
				d = $F('frm_deseado');
				return ( parseInt(e) < parseInt(d));
			}],
	['validate-stock', 'debe ser menor o igual al stock real', function (v,elm) {
				var p = elm.parentNode;
				var options = p.getElementsByTagName('INPUT');
				return (parseInt(v) <= parseInt(options[0].value));
			}],
	['validate-stock-deseado', 'este stock debe ser mayor o igual que el minimo', function(e) {
				m = $F('frm_alta_s_minimo');
				return ( parseInt(m) <= parseInt(e));
			}],
	['validate-pass', 'no coincide con la clave ingresada', function(e) {
			d = $F('clave');
			return (e== d);
	}]
]);