// 電卓の状態を管理する変数
// WHY:変数がグローバルスコープで宣言されていないと関数内で参照できない。
let currentInput = '0'; // ディスプレイに表示する値
let firstNumber = null; // 最初の値
let operator = null; // 演算子
let isWaitingForSecondNumber = false; // 2つ目の値を待っているかどうかを示すフラグ値

// 表示ディスプレイをクリアする
window.clearDisplay = function() {
    currentInput = '';
    firstNumber = null;
    operator = null;
    isWaitingForSecondNumber = false;
    updateDisplay();
};

// 表示ディスプレイを更新する
window.updateDisplay = function() {
    document.getElementById('display').textContent = currentInput;
};

// 数字を表示ディスプレイに追加する
window.appendNumber = function(number) {
    // 2つ目の値を待っている場合は、2つ目の値を更新する
    if (isWaitingForSecondNumber) {
        currentInput = number;
        isWaitingForSecondNumber = false;
    } else {
        // 2つ目の値を待っていない場合は、1つ目の値を更新する
        currentInput = (currentInput === '0' ? number : currentInput + number);
    }
    updateDisplay();
}

// 演算子が押下された場合の表示を制御する
window.appendOperator = function(operator) {
    // 演算子がnullでない場合
    if (operator != null) {
        // 1つ目の値がnullの場合
        if (firstNumber == null) {
            // 1つ目の値を更新
            firstNumber = parseFloat(currentInput);
        // 1つ目の値がnullでない場合 かつ 2つ目の値を待っていない場合
        } else if (!isWaitingForSecondNumber) {
            // 計算を実行
            // TODO:calculate();
            // 1つ目の値を更新(計算結果を1つ目の値として格納)
            firstNumber = parseFloat(currentInput);
        }
        // 演算子を更新
        operator = operator;
        // 2つ目の値を待っている場合はtrueにする
        isWaitingForSecondNumber = true;
        // 表示ディスプレイを更新
        updateDisplay();
    }
}

