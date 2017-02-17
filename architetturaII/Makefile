create:
	npm install
	./node_modules/markdown-pdf/bin/markdown-pdf description.md

pdf: create
	mv description.pdf crippa_riccardo_837449_arch2lab_specifica.pdf

zip: pdf
	cp progetto.asm crippa_riccardo_837449_arch2lab_progetto.asm
	zip crippa_riccardo_837449_arch2lab.zip crippa_riccardo_837449_arch2lab_specifica.pdf crippa_riccardo_837449_arch2lab_progetto.asm

clean:
	rm -Rf node_modules/
	rm crippa_riccardo_837449_arch2lab_specifica.pdf
	rm crippa_riccardo_837449_arch2lab_progetto.asm
	rm crippa_riccardo_837449_arch2lab.zip
