@props(['status'])

<div class="options-filter-wrapper">
    <div class="option-filter">
      <input class="appearance-none outline-none option-filter-input" {{ $status == 'pending' ? 'checked' : '' }} value="pending" name="status" type="radio" onclick="this.form.submit();" />
      <div class="option-filter-btn">
        <span class="option-filter-span">En attente</span>
      </div>
    </div>
    <div class="option-filter">
      <input class="appearance-none outline-none option-filter-input" {{ $status == 'corrected' ? 'checked' : '' }} value="corrected" name="status" type="radio" onclick="this.form.submit();" />
      <div class="option-filter-btn">
        <span class="option-filter-span">Corrigées</span>
      </div>
    </div>
</div>
  
  <style>
.options-filter-wrapper {
  --font-color-dark: #323232;
  --font-color-light: #FFF;
  --bg-color: #fff;
  --main-color: #323232;
  position: relative;
  width: 170px;
  height: 36px;
  background-color: var(--bg-color);
  border: 2px solid var(--main-color);
  border-radius: 34px;
  display: flex;
  flex-direction: row;
  box-shadow: 4px 4px var(--main-color);
}

.option-filter {
  width: 80.5px;
  height: 28px;
  position: relative;
  top: 2px;
  left: 2px;
}

.option-filter-input {
  width: 100%;
  height: 100%;
  position: absolute;
  left: 0;
  top: 0;
  appearance: none;
  cursor: pointer;
  opacity: 0;
}

.option-filter-btn {
  width: 100%;
  height: 100%;
  background-color: var(--bg-color);
  border-radius: 50px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.option-filter-span {
  color: var(--font-color-dark);
}

.option-filter-input:checked + .option-filter-btn {
  background-color: var(--main-color);
}

.option-filter-input:checked + .option-filter-btn .option-filter-span {
  color: var(--font-color-light);
}
</style>