function UnixFormatter(unix) {
    this.unix = unix;
    this.days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    this.months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    this.date = new Date(this.unix * 1000);
    this.dateDay = this.date.getDate();
    this.dayNumber = this.date.getDay();
    this.dayString = this.days[this.dayNumber];
    this.monthNumber = this.date.getMonth() + 1;
    this.monthString = this.months[this.date.getMonth()];
    this.year = this.date.getYear() + 1900;
    this.numberFormat = `${this.dateDay < 10 ? '0' + this.dateDay : this.dateDay}.${this.monthNumber < 10 ? '0' + this.monthNumber : this.monthNumber}.${this.year}`;
    this.textFormat = `${this.dayString}, ${this.dateDay}. ${this.monthString} ${this.year}`;
}