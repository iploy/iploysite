<?php
/**
 * PDF Export class for phpMyFAQ
 *
 * PHP Version 5.2
 *
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * @category  phpMyFAQ
 * @package   PMF_Export
 * @author    Thorsten Rinne <thorsten@phpmyfaq.de>
 * @copyright 2009-2012 phpMyFAQ Team
 * @license   http://www.mozilla.org/MPL/MPL-1.1.html Mozilla Public License Version 1.1
 * @link      http://www.phpmyfaq.de
 * @since     2009-10-07
 */

if (!defined('IS_VALID_PHPMYFAQ')) {
    exit();
}

/**
 * PMF_Export_Pdf
 *
 * @category  phpMyFAQ
 * @package   PMF_Export
 * @author    Thorsten Rinne <thorsten@phpmyfaq.de>
 * @copyright 2009-2012 phpMyFAQ Team
 * @license   http://www.mozilla.org/MPL/MPL-1.1.html Mozilla Public License Version 1.1
 * @link      http://www.phpmyfaq.de
 * @since     2009-10-07
 */
class PMF_Export_Pdf extends PMF_Export 
{    
    /**
     * PMF_Export_Pdf_Wrapper object
     * 
     * @var PMF_Export_Pdf_Wrapper
     */
    private $pdf = null;
    
    /**
     * Constructor
     * 
     * @param PMF_Faq      $faq      PMF_Faq object
     * @param PMF_Category $category PMF_Category object 
     * 
     * return PMF_Export_Pdf
     */
    public function __construct(PMF_Faq $faq, PMF_Category $category)
    {
        $this->faq      = $faq;
        $this->category = $category;
        $this->pdf      = new PMF_Export_Pdf_Wrapper();
        
        // Set PDF options
        $this->pdf->Open();
        $this->pdf->SetDisplayMode('real');
        $this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
    }
    
    /**
     * Generates the export
     * 
     * @param integer $categoryId Category Id
     * @param boolean $downwards  If true, downwards, otherwise upward ordering
     * @param string  $language   Language
     * 
     * @return string
     */
    public function generate($categoryId = 0, $downwards = true, $language = '')
    {
        // Set PDF options
        $this->pdf->enableBookmarks = true;
        $this->pdf->isFullExport    = true;

        // Initialize categories
        $this->category->transform($categoryId);
        
        $faqdata = $this->faq->get(FAQ_QUERY_TYPE_EXPORT_XML, $categoryId, $downwards, $language);
        $this->pdf->setCategory($categoryId);
        $this->pdf->setCategories($this->category->categoryName);
        
        if (count($faqdata)) {
            
            $categories = $questions = $answers = $authors = $dates = array();

            $i = 0;
            foreach ($faqdata as $data) {
                $categories[$i] = $data['category_id'];
                $questions[$i]  = $data['topic'];
                $answers[$i]    = $data['content'];
                $authors[$i]    = $data['author_name'];
                $dates[$i]      = $data['lastmodified'];
                $i++;
            }
            
            // Create the PDF
            foreach ($answers as $key => $value) {
                $this->pdf->setCategory($categories[$key]);
                $this->pdf->setQuestion($questions[$key]);
                $this->pdf->setCategories($this->category->categoryName);
                $this->pdf->AddPage();
                $this->pdf->SetFont($this->pdf->getCurrentFont(), '', 12);
                $this->pdf->WriteHTML($value);
            }

            // remove default header/footer
            $this->pdf->setPrintHeader(false);
            $this->pdf->addFaqToc();
        }
        
        return $this->pdf->Output('', 'S');
    }

    /**
     * Builds the PDF delivery for the given faq.
     *
     * @param array  $faqData
     * @param string $filename
     *
     * @return string
     */
    public function generateFile(Array $faqData, $filename = null)
    {
        global $PMF_LANG;

        // Default filename: pdf/<faq_id>.pdf
        if (empty($filename)) {
            $pdfFile = 'pdf' . $faqData['id'] . '.pdf';
        }

        $this->pdf->setFaq($faqData);
        $this->pdf->setCategory($faqData['category_id']);
        $this->pdf->setQuestion($faqData['title']);
        $this->pdf->setCategories($this->category->categoryName);

        // Set any item
        $this->pdf->SetTitle($faqData['title']);
        $this->pdf->SetCreator(
            PMF_Configuration::getInstance()->get('main.titleFAQ') .
            ' - powered by phpMyFAQ ' .
            PMF_Configuration::getInstance()->get('main.currentVersion')
        );
        $this->pdf->AddPage();
        $this->pdf->SetFont($this->pdf->getCurrentFont(), '', 12);
        $this->pdf->SetDisplayMode('real');
        $this->pdf->Ln();
        $this->pdf->WriteHTML(str_replace('../', '', $faqData['content']), true);
        $this->pdf->Ln();
        $this->pdf->Ln();
        $this->pdf->SetFont($this->pdf->getCurrentFont(), '', 11);
        $this->pdf->Write(5, $PMF_LANG['ad_entry_solution_id'] . ': #' . $faqData['solution_id']);
        $this->pdf->SetAuthor($faqData['author']);
        $this->pdf->Ln();
        $this->pdf->Write(5, $PMF_LANG['msgAuthor'] . ': ' . $faqData['author']);
        $this->pdf->Ln();
        $this->pdf->Write(5, $PMF_LANG['msgLastUpdateArticle'] . $faqData['date']);

        return $this->pdf->Output($pdfFile);
    }

}
