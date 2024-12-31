package Model;

public class DateTimeParser {
    String parserDate;
    int parserTime;

    public DateTimeParser(String parserDate, int parserTime) {
        this.parserDate = parserDate;
        this.parserTime = parserTime;
    }

    public String getParserDate() {
        return parserDate;
    }

    public void setParserDate(String parserDate) {
        this.parserDate = parserDate;
    }

    public int getParserTime() {
        return parserTime;
    }

    public void setParserTime(int parserTime) {
        this.parserTime = parserTime;
    }
}
